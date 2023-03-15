<?php

namespace App\Factories;

use Core\Abstractions\Factory;
use Core\Helpers\DateTime;
use Core\Helpers\Hash;

class UserFactory extends Factory
{
    public static function frame(): array
    {
        return [
            'name' => faker()->name(),
            'email' => faker()->safeEmail,
            'password' => Hash::hashPassword('password'),
            'created_at' => DateTime::currentDateTime(),
        ];
    }
}
