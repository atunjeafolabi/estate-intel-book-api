<?php

namespace Tests\Feature;

use App\Helpers\Transformer;
use App\Models\Author;
use App\Models\Book;
use App\Models\Country;
use App\Models\Publisher;
use App\Repositories\Contract\BookRepositoryInterface;
use App\Repositories\Eloquent\BookRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var BookRepositoryInterface
     */
    private $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->app->make(BookRepositoryInterface::class);
    }

    /**
     * @dataProvider bookInfoDataProvider
     * @param $bookInfo
     */
    public function test_save_book($bookInfo)
    {
        $this->bookRepository->saveBook($bookInfo);

        $this->assertDatabaseCount(Book::class, 1);
        $this->assertDatabaseHas(Book::class, [
            "name" => $bookInfo['name'],
            "isbn" => $bookInfo['isbn'],
            "number_of_pages" => $bookInfo['number_of_pages'],
            "release_date" => $bookInfo['release_date']
        ]);
    }

    /**
     * @dataProvider bookInfoDataProvider
     * @param $bookInfo
     */
    public function test_update_book($bookInfo)
    {
        $book = $this->generateBooks(1);

        $this->bookRepository->updateBook($bookInfo, $book->id);

        $this->assertDatabaseHas(Book::class, [
            "name" => $bookInfo['name'],
            "isbn" => $bookInfo['isbn'],
            "number_of_pages" => $bookInfo['number_of_pages'],
            "release_date" => $bookInfo['release_date'],

        ]);

        $this->assertDatabaseHas(Author::class, [
            "name" => $bookInfo['authors'],
        ]);

        $this->assertDatabaseHas(Country::class, [
            "name" => $bookInfo['country'],
        ]);

        $this->assertDatabaseHas(Publisher::class, [
            "name" => $bookInfo['publisher'],
        ]);
    }

    /**
     * @dataProvider bookInfoDataProvider
     * @param $bookInfo
     */
    public function test_throws_exception_when_updating_a_book_that_does_not_exist($bookInfo)
    {
        $this->expectException(ModelNotFoundException::class);

        $invalidBookId = 999;

        $this->bookRepository->updateBook($bookInfo, $invalidBookId);
    }

    public function test_find_all_books()
    {
        $this->generateBooks(5);

        $books = $this->bookRepository->findAll();

        $this->assertCount(5, $books);
    }

    public function test_can_delete_a_book()
    {
        $book = $this->generateBooks(1);

        $this->bookRepository->delete($book->id);

        $this->assertNull(
            $this->bookRepository->findOne($book->id)
        );
    }

    public function test_throws_exception_when_trying_to_delete_a_book_that_does_not_exist()
    {
        $this->expectException(ModelNotFoundException::class);

        $invalidBookId = 999;

        $this->bookRepository->delete($invalidBookId);
    }

    public function test_get_books_from_external_api()
    {
        $this->fakeExternalApi();
        $bookUrl = env('EXTERNAL_API');

        $books = $this->bookRepository->findBookFromExternalAPI($bookUrl);

        $this->assertEquals(
            $books,
            Transformer::transformBooksFromExternalApi($this::$booksTobeReturnedByExternalApi)
        );
    }

    public function test_get_a_book_by_name_from_external_api()
    {
        $nameOfBook = 'Awesome Book';

        $this->fakeExternalApi($nameOfBook);

        $bookUrl = env('EXTERNAL_API');

        $book = $this->bookRepository->findBookFromExternalAPI($bookUrl, $nameOfBook);

        $this->assertEquals(
            $book,
            Transformer::transformBooksFromExternalApi($this::searchBookToBeReturnedByExternalApi($nameOfBook))
        );
    }
}
