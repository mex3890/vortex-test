<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;
class K extends Model
{
    public string $table = 'k';

    public function y(): SelectBuilder
    {
        return $this->hasOne(Y::class, 'id', 'id', 'y_id');
    }
}
