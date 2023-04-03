<?php

namespace App\Factories;

use Core\Abstractions\Factory;
use Core\Helpers\DateTime;

class PostFactory extends Factory
{

    public static function frame(): array
    {
        return [
            'name' => faker()->text(100),
            'created_at' => DateTime::currentDateTime(),
        ];
    }
}
