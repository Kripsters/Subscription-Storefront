<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 5, 200), // price between 5 and 200
            'category_id' => fake()->numberBetween(0, 11),
            'image' => '/storage/images/' . fake()->randomElement([
                'Debugging-Duck.jpg',
                'Eloquent-JavaScript-Book.jpg',
                'Laravel-Book.jpg',
                'PHP-the-Right-Way-Book.jpg',
                'Stack-Overflow-Notebook.jpg'
            ]),
        ];
    }
}
