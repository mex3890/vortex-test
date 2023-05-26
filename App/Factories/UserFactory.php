<?php

namespace App\Factories;

use Core\Abstractions\Factory;

class UserFactory implements Factory
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
