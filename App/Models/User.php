<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;

class User extends Model
{
    public string $table = 'users';

    public function enterprise(): SelectBuilder
    {
        return $this->hasOne(Enterprise::class);
    }

    public function phone(): SelectBuilder
    {
        return $this->belongsToOne(Phone::class);
    }

    public function posts(): SelectBuilder
    {
        return $this->belongsToMany(Post::class);
    }

    public function cars(): SelectBuilder
    {
        return $this->hasMany(Car::class);
    }
}
