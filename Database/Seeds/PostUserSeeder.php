<?php

namespace Database\Seeds;

use App\Factories\PostUserFactory;
use Core\Abstractions\Seeder;

class PostUserSeeder extends Seeder
{
    public static function handler(): void
    {
        self::factory('post_user', PostUserFactory::class, 1000);
    }
}
