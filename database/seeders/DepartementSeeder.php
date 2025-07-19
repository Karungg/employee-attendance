<?php

namespace Database\Seeders;

use App\Models\Departement;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departement::create([
            'departement_name' => "Departemen 1",
            'max_clock_in_time' => Carbon::createFromTimeString("08:00:00"),
            'max_clock_out_time' => Carbon::createFromTimeString("17:00:00")
        ]);
    }
}
