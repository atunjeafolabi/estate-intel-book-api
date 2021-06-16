<?php

namespace App\Repositories\Contract;

use Derakht\RepositoryPattern\Repositories\RepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface BookRepositoryInterface extends RepositoryInterface
{
    public function create(array $data);
    public function findAll() : EloquentCollection;
    public function findOne($id);
    public function findBookFromExternalAPI(string $bookUrl, string $nameOfBook) : Collection;
    public function saveBook(array $bookInfo);
}
