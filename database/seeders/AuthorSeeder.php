<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::insert([
            ['name' => 'Edgar Allan Poe'],
            ['name' => 'William Shakespeare'],
            ['name' => 'Octavio Paz'],
            ['name' => 'Juan Rulfo'],
            ['name' => 'Dante Alighieri'],
        ]);
    }
}
