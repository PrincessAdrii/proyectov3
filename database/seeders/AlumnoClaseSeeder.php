<?php

namespace Database\Seeders;

use App\Models\AlumnosClase;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlumnoClaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AlumnosClase::factory()->count(3)->create();
    }
}
