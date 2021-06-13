<?php

namespace App\Repositories\Eloquent;

use Derakht\RepositoryPattern\Repositories\Repository;
use App\Repositories\Contract\AuthorRepositoryInterface;
use App\Models\Author;

/** @property Author $model */
class AuthorRepository extends Repository implements AuthorRepositoryInterface
{
    protected function model()
    {
        return Author::class;
    }
}
