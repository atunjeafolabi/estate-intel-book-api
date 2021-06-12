<?php

namespace App\Repositories\Eloquent;

use Derakht\RepositoryPattern\Repositories\Repository;
use App\Repositories\Contract\BookRepositoryInterface;
use App\Models\Book;

/** @property Book $model */
class BookRepository extends Repository implements BookRepositoryInterface
{
    protected function model()
    {
        return Book::class;
    }
}
