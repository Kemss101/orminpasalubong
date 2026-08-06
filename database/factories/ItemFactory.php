<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use APP\Models\Item;
use App\Models\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([]),
                'description' => $this->faker->sentence(),
                'price' => $this->faker->randomFloat(2, 10, 1000),
                'stock' => $this->faker->numberBetween(1, 100),
                'id_category' => Category::inRandomOrder()->first()?->id_category
                ?? Category::factory(),

        ];
    }
}
