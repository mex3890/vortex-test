<?php

namespace App\Models;

use Core\Abstractions\Model;

class Enterprise extends Model
{
    public string $table = 'enterprises';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
