<?php

namespace App\Interceptors;

use Core\Interceptors\Interceptor;
use Core\Abstractions\Model;

class UserInterceptor extends Interceptor
{
    public static function creating(array $args): array
    {
        return $args;
    }

    public static function created(Model $model)
    {
        return $model;
    }

    public static function updating(array $args): array
    {
        return $args;
    }

    public static function updated(Model $model)
    {
        return $model;
    }

    public static function deleting(Model $model)
    {
        return $model;
    }

    public static function deleted(int $model_id)
    {
        return $model_id;
    }
}