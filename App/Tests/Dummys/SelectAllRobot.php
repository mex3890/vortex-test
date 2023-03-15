<?php

namespace App\Tests\Dummys;

use App\Tests\DummyRobot;
use Core\Database\Schema;

class SelectAllRobot extends DummyRobot
{
    public static function action()
    {
        Schema::select('dummy')->get();
    }
}
