<?php

namespace App\Factories;

use Core\Abstractions\Factory;
use Exception;

class CarFactory extends Factory
{

    /**
     * @throws Exception
     */
    public static function frame(): array
    {
        return [
            'name' => faker()->word,
            'brand' => faker()->word,
            'user_id' => random_int(1, 200)
        ];
    }
}
