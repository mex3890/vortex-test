<?php

namespace App\Services;

use App\Models\User;
use Core\Abstractions\Service;
use Core\Helpers\Hash;

class VerifyUser extends Service
{
    public static function verifyUserByCredentials(string $email, string $password): bool
    {
        $user = User::find()->where('email', $email)->get();

        if ($user instanceof User) {
            return Hash::verifyPassword($password, $user->password);
        }

        return false;
    }
}
