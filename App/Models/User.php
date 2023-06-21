<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;

class User extends Model
{
    public string $table = 'users';

    public function posts(): SelectBuilder
    {
        return $this->belongsToMany(Post::class, 'id', 'user_id', 'post_user', 'id', 'post_id');
    }

    public function teams(): SelectBuilder
    {
        return $this->belongsTo(Team::class, 'id', 'id', 'team_id');
    }
}
