<?php

namespace App\Repositories\Contract;

use App\Models\Book;
use Derakht\RepositoryPattern\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface BookRepositoryInterface extends RepositoryInterface
{
    public function findAll(array $searchBy) : EloquentCollection;
    public function findOne($id) : ?Book;
    public function findBookFromExternalAPI(string $bookUrl, string $nameOfBook);
    public function saveBook(array $bookInfo) : Book;
    public function updateBook(array $bookInfo, $id) : Book;
    public function delete($id) : Book;
}
