<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;

class Car extends Model
{
    public string $table = 'cars';

    public function phone(): SelectBuilder
    {
        return $this->hasOne(
            Phone::class,
            'id',
            'user_id',
            'user_id');
    }
}
