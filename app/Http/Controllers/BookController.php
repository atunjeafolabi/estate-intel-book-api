<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Http\Resources\DeletedResource;
use App\Repositories\Contract\BookRepositoryInterface;
use Illuminate\Http\Request;

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
        // TODO: validation

        $newBookInformation = $this->request->toArray();

        $book = $this->bookRepository->saveBook($newBookInformation);

        return new BookResource($book);
    }

    public function update($id)
    {
        // TODO: validation

        $bookInformation = $this->request->toArray();

        $book = $this->bookRepository->updateBook($bookInformation, $id);

        return new BookResource($book, true);
    }

    public function index()
    {
        $books = $this->bookRepository->findAll();

        return new BookCollection($books);
    }

    public function show($id)
    {
        $book = $this->bookRepository->findOne($id);

        return new BookResource($book, true);
    }

    public function delete($id)
    {
        $book = $this->bookRepository->delete($id);

        return new DeletedResource($book);
    }

    public function booksFromExternalApi()
    {
        $bookUrl = env('EXTERNAL_API');

        $nameOfBook = request()->query('nameOfBook');

        $books = $this->bookRepository->findBookFromExternalAPI($bookUrl);

        return new BookCollection($books, false);
    }
}
