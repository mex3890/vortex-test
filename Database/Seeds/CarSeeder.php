<?php

namespace Database\Seeds;

use App\Factories\CarFactory;
use Core\Abstractions\Seeder;

class CarSeeder extends Seeder
{
    public static function handler(): void
    {
        self::factory('cars', CarFactory::class, 500);
    }
}
