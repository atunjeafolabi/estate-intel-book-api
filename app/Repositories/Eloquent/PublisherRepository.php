<?php

namespace App\Repositories\Eloquent;

use Derakht\RepositoryPattern\Repositories\Repository;
use App\Repositories\Contract\PublisherRepositoryInterface;
use App\Models\Publisher;

/** @property Publisher $model */
class PublisherRepository extends Repository implements PublisherRepositoryInterface
{
    protected function model()
    {
        return Publisher::class;
    }

    public function findPublisherByName($name)
    {
        return Publisher::where(['name' => $name])->first();
    }
}
