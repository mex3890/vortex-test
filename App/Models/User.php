<?php

namespace App\Models;

use Core\Abstractions\Model;

class User extends Model
{
    public string $table = 'users';

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }
}
