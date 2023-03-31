<?php

namespace App\AutoRelation\Tests;

use Core\Abstractions\Model;
use Core\Helpers\ClassManager;
use Exception;

class DummyTest
{
    public static function modelInsert(string $model, array $column_values)
    {
        try {
            /** @var Model $object */
            $object = new $model($column_values);
            return [
                'Model' => ClassManager::getClassName($object, false),
                'Test' => 'Insert from Model',
                'Status' => 'OK'
            ];
        } catch (Exception $exception) {
            return [
                'Model' => ClassManager::getClassName($object, false),
                'Test' => 'Insert from Model',
                'Status' => 'X',
                'Exception' => $exception->getMessage()
            ];
        }
    }
}
