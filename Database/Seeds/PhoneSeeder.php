<?php

namespace Database\Seeds;

use Core\Abstractions\Seeder;

class PhoneSeeder extends Seeder
{
    public static function handler(): void
    {
        for ($i = 1; $i <= 200; $i++) {
            self::create('phones', [
                'number' => '21954456557',
                'user_id' => $i,
            ]);
        }
    }
}
