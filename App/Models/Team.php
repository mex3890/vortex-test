<?php

namespace App\Models;

use Core\Abstractions\Model;
use Core\Database\Query\SelectBuilder;

class Team extends Model
{
    public string $table = 'teams';

    public function users(): SelectBuilder
    {
        return $this->hasMany(User::class, 'id', 'team_id');
    }
}
