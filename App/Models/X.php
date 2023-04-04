<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;
class X extends Model
{
    public string $table = 'x';

    public function ks(): SelectBuilder
    {
        return $this->hasManyThrough(K::class, Y::class);
    }
}
