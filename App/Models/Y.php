<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;
class Y extends Model
{
    public string $table = 'y';

    public function ks(): SelectBuilder
    {
        return $this->hasMany(K::class, 'y_id');
    }

    public function x(): SelectBuilder
    {
        return $this->hasOne(X::class, 'id', 'id', 'x_id');
    }
}
