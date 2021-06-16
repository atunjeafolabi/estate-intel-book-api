<?php

namespace Tests\Feature;

use App\Helpers\Status;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Http\Resources\DeletedResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider bookInfoDataProvider
     * @param $bookInfo
     */
    public function test_can_create_book($bookInfo)
    {
        $book = new Book($bookInfo);
        $bookResource = (new BookResource($book))
            ->response()
            ->getData(true);

        // hit endpoint to create book
        $response = $this->postJson('api/v1/books', $bookInfo);

        // assert that book has been created
        $response->assertCreated();
        $response->assertExactJson($bookResource);
    }

    /**
     * @dataProvider bookInfoDataProvider
     * @param $bookInfo
     */
    public function test_can_update_book($bookInfo)
    {
        $book = $this->generateBooks(1);

        $updatedBook = new Book($bookInfo);
        $updatedBook->id = $book->id;

        $bookResource = (new BookResource($updatedBook, true))
            ->response()
            ->getData(true);

        // hit endpoint to update book
        $response = $this->patchJson("api/v1/books/$book->id", $bookInfo);

        // assert that book has been created
        $response->assertSuccessful();
        $response->assertExactJson($bookResource);
    }

    /**
     * @dataProvider bookInfoDataProvider
     * @param $bookInfo
     */
    public function test_throws_exception_when_updating_a_book_that_does_not_exist($bookInfo)
    {
        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        $invalidBookId = 999;

        // hit endpoint to update book
        $response = $this->patchJson("api/v1/books/$invalidBookId", $bookInfo);

        $response->assertSuccessful();
    }

    public function test_can_get_one_book()
    {
        $book = $this->generateBooks(1);

        $bookResource = (new BookResource($book, true))
            ->response()
            ->getData(true);

        $response = $this->get("api/v1/books/$book->id");

        $response->assertOk();
        $response->assertExactJson($bookResource);
    }

    public function test_returns_empty_data_if_a_book_does_not_exist()
    {
        $invalidBookId = 9999;

        $response = $this->get("api/v1/books/$invalidBookId");

        $response->isSuccessful();
        $response->assertExactJson([
            'status_code' => Status::HTTP_OK,
            'status' => 'success',
            'data' => []
        ]);
    }

    public function test_can_get_all_books()
    {
        $books = $this->generateBooks(5);

        $booksResource = (new BookCollection($books))
            ->response()
            ->getData(true);

        $response = $this->get('api/v1/books');

        $response->assertOk();
        $response->assertExactJson($booksResource);
    }

    public function test_returns_empty_data_if_no_books_found()
    {
        $response = $this->get('api/v1/books');

        $response->assertSuccessful();
        $response->assertExactJson([
            'status_code' => Status::HTTP_OK,
            'status' => 'success',
            'data' => []
        ]);
    }

    public function test_can_delete_a_book()
    {
        // Put a book in the database
        $book = $this->generateBooks(1);

        $deletedResource = (new DeletedResource($book))
            ->response()
            ->getData(true);

        // hit endpoint
        $response = $this->deleteJson("api/v1/books/$book->id");

        // try to find the deleted book in database
        $book = Book::find($book->id);

        // assert that the book cannot be found
        $response->assertOk();
        $this->assertNull($book);
        $response->assertExactJson($deletedResource);
    }

    public function test_throws_exception_when_trying_to_delete_a_book_that_does_not_exist()
    {
        $this->generateBooks(1);
        $invalidBookId = 9999;

        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        $response = $this->deleteJson("api/v1/books/$invalidBookId");

        $response->assertNotFound();
    }

    public function test_can_get_books_from_external_api()
    {
        // Mock a fake external url
        $this->fakeExternalApi();

        // Hit endpoint to get books
        $response = $this->getJson("api/external-books");

        // Assert response
        $response->assertExactJson($response->json());
    }
}
