<?php

namespace App\Factories;

use Core\Abstractions\Factory;
use Core\Helpers\DateTime;

class DummyFactory extends Factory
{

    public static function frame(): array
    {
        return [
            'fake_name' => faker()->firstName() . ' ' . faker()->lastName,
            'money' => faker()->randomFloat(2),
            'created_at' => DateTime::currentDateTime(),
            'updated_at' => DateTime::currentDateTime(),
            'deleted_at' => DateTime::currentDateTime(),
        ];
    }
}
