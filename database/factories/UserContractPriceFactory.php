<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserContractPrice>
 */
class UserContractPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1,10),
            'price' => fake()->numberBetween(10,10000000),
            'sku' => fake()->unique()->randomElement(Product::pluck('sku')->all()),
            // Unique za kombinackiju user_id sku
        ];
    }
}
