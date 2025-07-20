<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Departement>
 */
class DepartementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'departement_name' => "Departemen " . fake()->word(5),
            'max_clock_in_time' => Carbon::createFromTimeString("08:00:00"),
            'max_clock_out_time' => Carbon::createFromTimeString("17:00:00")
        ];
    }
}
