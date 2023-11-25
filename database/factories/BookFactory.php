<?php

// database/factories/BookFactory.php

namespace Database\Factories;

use App\Models\Book;
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
        return [
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name(),
            'genre' => $this->faker->randomElement(['Ea', 'Return', 'Et', 'Dolorem', 'Quia']),
            'description' => $this->faker->sentence(100),
            'image' => $this->faker->image,
            'isbn' => rand(1, mt_getrandmax()) . str_pad(mt_rand(1, 999), 12, '0', STR_PAD_LEFT),
            'published' => $this->faker->dateTimeBetween('now', 'now')->format('Y-m-d'),
            'publisher' => $this->faker->name(),
        ];
    }
}

