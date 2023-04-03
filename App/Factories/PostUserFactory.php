<?php

namespace App\Factories;

use Core\Abstractions\Factory;
use Exception;

class PostUserFactory extends Factory
{

    /**
     * @throws Exception
     */
    public static function frame(): array
    {
        return [
            'post_id' => random_int(1, 400),
            'user_id' => random_int(1, 200),
        ];
    }
}
