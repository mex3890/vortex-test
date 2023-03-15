<?php

use App\Tests\Dummys\SelectAllRobot;

return [
    [
        'class' => SelectAllRobot::class,
        'count' => 1,
        'store_in-database' => true,
        'test_name' => 'Select All',
    ],
    [
        'class' => SelectAllRobot::class,
        'count' => 10,
        'store_in-database' => true,
        'test_name' => 'Select All',
    ],
    [
        'class' => SelectAllRobot::class,
        'count' => 100,
        'store_in-database' => true,
        'test_name' => 'Select All',
    ],
    [
        'class' => SelectAllRobot::class,
        'count' => 1000,
        'store_in-database' => true,
        'test_name' => 'Select All',
    ],
];
