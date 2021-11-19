<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            ['name' => 'Mystery', 'description' => 'Mystery novels, also called detective fiction.'],
            ['name' => 'Horror', 'description' => 'Horror novels are meant to scare, startle, shock, and even repulse readers.'],
            ['name' => 'Historical', 'description' => 'Historical fiction novels take place in the past.'],
            ['name' => 'Romance', 'description' => 'Romantic fiction centers around love stories between two people.'],
            ['name' => 'Science Fiction', 'description' => 'Sci-fi novels are speculative stories with imagined elements that don’t exist in the real world.'],
        ]);
    }
}
