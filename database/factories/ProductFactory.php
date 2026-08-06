<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Product>
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
            'city'=>$this->faker->city(),
            'country'=>$this->faker->country(),
            'year_listed'=>$this->faker->year(),
            'number_of_rooms'=>$this->faker->numberBetween(1,10),
        ];
    }
}
