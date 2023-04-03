<?php

namespace Database\Seeds;

use App\Factories\UserFactory;
use Core\Abstractions\Seeder;

class UserSeeder extends Seeder
{
    public static function handler(): void
    {
        self::factory('users', UserFactory::class, 200);
    }
}
