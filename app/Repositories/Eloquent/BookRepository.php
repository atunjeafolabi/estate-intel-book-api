<?php

namespace App\Repositories\Eloquent;

use App\Helpers\Transformer;
use App\Repositories\Contract\AuthorRepositoryInterface;
use App\Repositories\Contract\PublisherRepositoryInterface;
use Derakht\RepositoryPattern\Repositories\Repository;
use App\Repositories\Contract\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Http;

/** @property Book $model */
class BookRepository extends Repository implements BookRepositoryInterface
{
    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;
    /**
     * @var PublisherRepositoryInterface
     */
    private $publisherRepository;
    /**
     * @var CountryRepository
     */
    private $countryRepository;

    public function __construct(
        App $app,
        AuthorRepositoryInterface $authorRepository,
        PublisherRepositoryInterface $publisherRepository,
        CountryRepository $countryRepository
    ) {
        parent::__construct($app);
        $this->authorRepository = $authorRepository;
        $this->publisherRepository = $publisherRepository;
        $this->countryRepository = $countryRepository;
    }

    protected function model()
    {
        return Book::class;
    }

    public function saveBook(array $newBookInformation) : Book
    {
        $publisher = $this->publisherRepository->create(['name' => $newBookInformation['publisher']]);

        $country = $this->countryRepository->create(['name' => $newBookInformation['country']]);

        $book = $this->create(
            Transformer::transformBook($newBookInformation, $publisher, $country)
        );

        $this->saveAuthorsForBook($newBookInformation, $book);

        return $book;
    }

    public function updateBook($bookInformation, $id) : Book
    {
        $book = $this->findOne($id);

        // update book
        $book->name = $bookInformation['name'];
        $book->isbn = $bookInformation['isbn'];
        $book->number_of_pages = $bookInformation['number_of_pages'];
        $book->release_date = $bookInformation['release_date'];

        // update publisher
        $book->publisher->name = $bookInformation['publisher'];

        // update country
        $book->country->name = $bookInformation['country'];

        $book->push();

        // update Authors
        $book->authors()->detach();

        foreach($book->authors as $author){
            $author->delete();
        };

        $this->saveAuthorsForBook($bookInformation, $book);

        $book->refresh();

        return $book;
    }

    public function findAll() : EloquentCollection
    {
        $books = Book::with(['authors', 'publisher'])->get();

        return $books;
    }

    public function findOne($id) : ?Book
    {
        try{
            $book = Book::findOrFail($id);
            $book->load(['publisher', 'authors', 'country']);
        }catch (ModelNotFoundException $e){
            $book = null;
        }

        return $book;
    }

    public function delete($id) : Book
    {
        $book = $this->findOrFail($id);

        $this->deleteBookAndTheirAuthors($book);

        return $book;
    }

    public function findBookFromExternalAPI($nameOfBook = null) : Collection
    {
        $bookUrl = env('EXTERNAL_API');

        if($nameOfBook){
            $bookUrl = $bookUrl . "?name=" . $nameOfBook;
        }

        $response = Http::get($bookUrl)->json();

        return Transformer::transformBooksFromExternalApi($response);
    }

    public function deleteBookAndTheirAuthors(Book $book)
    {
        $book->authors()->detach();
        $book->delete();
    }

    /**
     * @param array $newBookInformation
     * @param $book
     */
    protected function saveAuthorsForBook(array $newBookInformation, $book)
    {
        foreach ($newBookInformation['authors'] as $author) {
            $author = $this->authorRepository->create(['name' => $author]);

            $book->authors()->attach($author->id);
        }
    }
}
