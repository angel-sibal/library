<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'author' => $this->faker->name(),
            'stocks' => $this->faker->numberBetween(0, 100),
            'cover_image_filepath' => null,
        ];
    }
}
