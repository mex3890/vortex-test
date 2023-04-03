<?php

namespace Database\Seeds;

use App\Factories\EnterpriseFactory;
use Core\Abstractions\Seeder;

class EnterpriseSeeder extends Seeder
{
    public static function handler(): void
    {
        self::factory('enterprises', EnterpriseFactory::class, 100);
    }
}
