<?php

namespace App\Repositories\Eloquent;

use Derakht\RepositoryPattern\Repositories\Repository;
use App\Repositories\Contract\CountryRepositoryInterface;
use App\Models\Country;

/** @property Country $model */
class CountryRepository extends Repository implements CountryRepositoryInterface
{
    protected function model()
    {
        return Country::class;
    }
}
