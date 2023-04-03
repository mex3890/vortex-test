<?php

namespace App\Factories;

use Core\Abstractions\Factory;

class EnterpriseFactory extends Factory
{

    public static function frame(): array
    {
        return [
            'name' => faker()->lastName,
            'address' => faker()->streetAddress
        ];
    }
}
