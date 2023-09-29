<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = strtotime(date('Y-m-d') .' -1 year');
        return [
            "book_id" => Book::all()->random(1)->pluck('id')->first() ,
            "quantity" => $this->faker->randomDigitNotNull(),
            "created_at" => $this->faker->dateTimeBetween(date('Y-m-d', $date), date('d.m.Y'))
        ];
    }
}
