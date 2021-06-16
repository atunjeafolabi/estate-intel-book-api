<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Country;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $publisher = Publisher::factory()->create();

        $country = Country::factory()->create();

        return [
            "name" => $this->faker->name,
            "isbn" => $this->faker->isbn10,
            "number_of_pages" => $this->faker->randomNumber(),
            "publisher_id" => $publisher->id,
            "country_id" => $country->id,
            "release_date" => $this->faker->date()
        ];
    }
}
