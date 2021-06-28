<?php

namespace App\Repositories\Eloquent;

use App\Helpers\Transformer;
use App\Repositories\Contract\AuthorRepositoryInterface;
use App\Repositories\Contract\PublisherRepositoryInterface;
use Derakht\RepositoryPattern\Repositories\Repository;
use App\Repositories\Contract\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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
    /**
     * @var Book
     */
    private $book;

    public function __construct(
        App $app,
        AuthorRepositoryInterface $authorRepository,
        PublisherRepositoryInterface $publisherRepository,
        CountryRepository $countryRepository
    ) {
        parent::__construct($app);
        $this->authorRepository    = $authorRepository;
        $this->publisherRepository = $publisherRepository;
        $this->countryRepository   = $countryRepository;
    }

    protected function model()
    {
        return Book::class;
    }

    public function saveBook(array $newBookInformation): Book
    {
        $publisher
            = $this->publisherRepository->create(['name' => $newBookInformation['publisher']]);

        $country
            = $this->countryRepository->create(['name' => $newBookInformation['country']]);

        $book = $this->create(
            Transformer::transformBook(
                $newBookInformation,
                $publisher,
                $country
            )
        );

        $this->saveAuthorsForBook($newBookInformation, $book);

        return $book;
    }

    public function updateBook($bookInformation, $id): Book
    {
        $book = $this->findOne($id);

        if (! $book) {
            throw new ModelNotFoundException();
        }

        // update book
        $book->name            = $bookInformation['name'];
        $book->isbn            = $bookInformation['isbn'];
        $book->number_of_pages = $bookInformation['number_of_pages'];
        $book->release_date    = $bookInformation['release_date'];

        // update publisher
        $book->publisher->name = $bookInformation['publisher'];

        // update country
        $book->country->name = $bookInformation['country'];

        $book->push();

        // update Authors
        $book->authors()->detach();
        foreach ($book->authors as $author) {
            $author->delete();
        };

        $this->saveAuthorsForBook($bookInformation, $book);

        $book->refresh();

        return $book;
    }

    /**
     * @param   array  $newBookInformation
     * @param          $book
     */
    private function saveAuthorsForBook(array $newBookInformation, Book $book)
    {
        foreach ($newBookInformation['authors'] as $author) {
            $author = $this->authorRepository->create(['name' => $author]);

            $book->authors()->attach($author->id);
        }
    }

    public function findAll(array $searchBy = []): EloquentCollection
    {
        $bookBuilder = $this->getModel()->with(['authors', 'publisher']);

        if (!$searchBy) {
            $books = $bookBuilder->get();
            return $books;
        }

        // search by country
        if (array_key_exists('country', $searchBy)) {
            $country = $this->countryRepository->findCountryByName($searchBy['country']);
            if (!$country) {
                throw new ModelNotFoundException('Invalid Country');
            }
            $bookBuilder->where(['country_id' => $country->id]);
        }

        // search by publisher
        if (array_key_exists('publisher', $searchBy)) {
            $publisher = $this->publisherRepository->findPublisherByName($searchBy['publisher']);
            if (!$publisher) {
                throw new ModelNotFoundException('Invalid Publisher');
            }
            $bookBuilder->where(['publisher_id' => $publisher->id]);
        }

        // search by release date
        if (array_key_exists('release_date', $searchBy)) {
            $bookBuilder->where('release_date', $searchBy['release_date']);
        }

        // search by name of book
        if (array_key_exists('name', $searchBy)) {
            $bookBuilder->where(['name' => $searchBy['name']]);
        }

        $books = $bookBuilder->get();

        return $books;
    }

    public function findOne($id): ?Book
    {
        $book = $this->getModel()->find($id);

        if (!$book) {
            return null;
        }

        return $book->load(['publisher', 'authors', 'country']);
    }

    public function delete($id): Book
    {
        $book = $this->findOrFail($id);

        $this->deleteBookAndTheirAuthors($book);

        return $book;
    }

    /**
     * @param   Book  $book
     */
    private function deleteBookAndTheirAuthors(Book $book)
    {
        $book->authors()->detach();
        $book->delete();
    }

    public function findBookFromExternalAPI(
        string $bookUrl,
        string $nameOfBook = null
    ) {

        if ($nameOfBook) {
            $bookUrl = $bookUrl . "?name=" . $nameOfBook;
        }

        return Transformer::transformBooksFromExternalApi(
            Http::get($bookUrl)->json()
        );
    }
}
