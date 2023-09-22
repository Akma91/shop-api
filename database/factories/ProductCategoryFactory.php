<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(5, true),
            'level' => fake()->numberBetween(0, 10),
            'parent_node' => fake()->numberBetween(0, 100),
            'node' => fake()->numberBetween(0, 100),
        ];
    }
}
