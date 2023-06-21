<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;

class Post extends Model
{
    public string $table = 'posts';

    public function users(): SelectBuilder
    {
        return $this->belongsToMany(User::class, 'id', 'post_id', 'post_user', 'id', 'user_id');
    }
}
