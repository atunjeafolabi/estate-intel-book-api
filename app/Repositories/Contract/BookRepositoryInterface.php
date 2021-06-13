<?php

namespace App\Repositories\Contract;

use Derakht\RepositoryPattern\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface BookRepositoryInterface extends RepositoryInterface
{
    public function create(array $data);
    public function findAll() : Collection;
    public function findOne($id);
    public function findBookFromExternalAPI($nameOfBook);
}
