<?php

namespace Database\Seeds;

use App\Factories\UserFactory;
use Core\Abstractions\Seeder;
use Core\Database\Schema;
use Core\Helpers\DateTime;
use Core\Helpers\Hash;

class UserSeeder extends Seeder
{
    public static function handler(): void
    {
        Schema::insert('users', [
            'name' => 'admin',
            'email' => 'admin@vortex.com',
            'password' => Hash::hashPassword('password'),
            'created_at' => DateTime::currentDateTime(),
        ])->get();

        self::factory('users', UserFactory::class, 20);
    }
}
