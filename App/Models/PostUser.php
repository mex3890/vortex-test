<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;

class PostUser extends Model
{
    public string $table = 'post_user';

    public function users(): SelectBuilder
    {
        return $this->belongsTo(User::class, 'id', 'id', 'user_id');
    }

    public function posts(): SelectBuilder
    {
        return $this->belongsTo(Post::class, 'id', 'id', 'post_id');
    }
}
