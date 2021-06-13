<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Http\Resources\DeletedResource;
use App\Http\Resources\NoResultsResource;
use App\Repositories\Contract\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Helpers\Status;
use Illuminate\Http\Response as LaraResponse;
use Illuminate\Support\Collection;

class BookController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    private $bookRepository;
    private $request;

    public function __construct(BookRepositoryInterface $bookRepository, Request $request)
    {
        $this->bookRepository = $bookRepository;
        $this->request = $request;
    }

    public function create()
    {

        $newBookInformation = $this->request->toArray();

        // TODO: validation

        $book = $this->bookRepository->saveBook($newBookInformation);

        return $this->response($book);
    }

    public function update($id)
    {
        // TODO: validation

        $bookInformation = $this->request->toArray();

        $book = $this->bookRepository->updateBook($bookInformation, $id);

        return $this->response($book);
    }

    public function index()
    {
        $books = $this->bookRepository->findAll();

        return $this->response($books);
    }

    public function show($id)
    {
        $book = $this->bookRepository->findOne($id);

        return $this->response($book);
    }

    public function delete($id)
    {
        $book = $this->bookRepository->delete($id);

        return new DeletedResource($book);
    }

    public function booksFromExternalApi()
    {
        $nameOfBook = request()->query('nameOfBook');

        $books = $this->bookRepository->findBookFromExternalAPI($nameOfBook);

        return $this->response($books);
    }

    /**
     * @param $model
     * @return LaraResponse
     */
    protected function response($model)
    {
        $resource = is_null($model) ? null : $this->getResource($model);

        return new LaraResponse(new Response($resource));
    }

    /**
     * @param $model
     * @return BookCollection|BookResource
     */
    protected function getResource($model)
    {
        return $this->isCollection($model) ?
            new BookCollection($model) : new BookResource($model);
    }

    /**
     * @param $model
     * @return bool
     */
    protected function isCollection($model)
    {
        return ($model instanceof EloquentCollection) || ($model instanceof Collection);
    }
}
