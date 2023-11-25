<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
        	'name' => 'Admin', 
        	'email' => 'admin@gmail.com',
        	'is_admin' => '1',
        	'password' => bcrypt('123456'),
        ]);
  
    }
}
