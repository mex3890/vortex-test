<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;

class T0 extends Model
{
    public string $table = 't0';

    public function t0(): SelectBuilder
    {
        return $this->hasOne(T0::class, 't0_id');
    }
}
