<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    use HasFactory;

    /**
     * Get the country of the book.
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * Get the publisher of the book.
     */
    public function publisher()
    {
        return $this->belongsTo('App\Models\Publisher');
    }

    /**
     * The authors that wrote the book.
     */
    public function authors()
    {
        return $this->belongsToMany('App\Models\Author');
    }
}
