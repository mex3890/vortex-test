<?php

namespace App\SchemaEngine\AutoRelation;

enum Relationships: string
{
    case BELONGS_TO = 'belongsTo';
    case BELONGS_TO_MANY = 'belongsToMany';
    case HAS_MANY = 'hasMany';
    case HAS_MANY_THROUGH = 'hasManyThrough';
    case HAS_ONE = 'hasOne';
    case HAS_ONE_THROUGH = 'hasOneThrough';
}
