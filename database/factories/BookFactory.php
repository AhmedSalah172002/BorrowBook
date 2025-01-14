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

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'isbn' => $this->faker->unique()->isbn13,
            'description' => $this->faker->paragraph,
            'genre_id' => 1,
            'pages' => $this->faker->numberBetween(100, 1000),
            'publication_year' => $this->faker->year,
            'cover_image' => $this->faker->imageUrl,
            'available_quantity' => $this->faker->numberBetween(1, 100),
            'borrowed_quantity' => 0,
            'is_active' => true,
        ];
    }
}
