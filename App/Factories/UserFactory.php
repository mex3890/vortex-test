<?php

namespace App\Factories;

use Core\Abstractions\Factory;
use Core\Helpers\DateTime;
use Core\Helpers\Hash;
use Exception;

class UserFactory extends Factory
{
    /**
     * @throws Exception
     */
    public static function frame(): array
    {
        return [
            'name' => faker()->name(),
            'email' => faker()->safeEmail,
            'password' => Hash::hashPassword('password'),
            'created_at' => DateTime::currentDateTime(),
            'enterprise_id' => random_int(1, 100),
        ];
    }
}
