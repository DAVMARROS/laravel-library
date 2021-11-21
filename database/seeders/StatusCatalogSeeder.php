<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            ['name' => 'Requested'],
            ['name' => 'Taken'],
            ['name' => 'Not Taken'],
            ['name' => 'Returned'],
            ['name' => 'Expired'],
        ]);
    }
}
