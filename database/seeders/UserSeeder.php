<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Bonilla Ramírez', 
                'last_name'=>'Diana Carolina',
                'email'=>'diana@twobits.com.mx',
                'password' => password_hash("maniak.co", PASSWORD_DEFAULT),
                'role_id' => 1
            ],
        ]);
    }
}
