<?php

namespace App\Services;

use App\Models\User;
use Core\Abstractions\Service;
use Core\Helpers\DateTime;
use Exception;

class CreateUser extends Service
{
    public static function make(string $name, string $email, string $password): bool|User
    {
        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => DateTime::currentDateTime()
        ]);

        try {
            $user = $user->create();
        } catch (Exception) {
            return false;
        }

        return $user;
    }
}
