<?php

namespace Database\Seeds;

use App\Factories\DummyFactory;
use Core\Abstractions\Seeder;

class DummySeeder extends Seeder
{
    public static function handler(): void
    {
        self::factory('dummy', DummyFactory::class, 1000);
    }
}
