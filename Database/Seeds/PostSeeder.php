<?php

namespace Database\Seeds;

use App\Factories\PostFactory;
use Core\Abstractions\Seeder;

class PostSeeder extends Seeder
{
    public static function handler(): void
    {
        self::factory('posts', PostFactory::class, 400);
    }
}
