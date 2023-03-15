<?php

namespace Database\Migrations;

use App\Tests\Conductor;
use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateTestTable extends Migration
{
    public static function up(): void
    {
        Schema::create(Conductor::TEST_TABLE_NAME, function (TableBuilder $table) {
            $table->id();
            $table->varchar('name', 255);
            $table->float('result');
            $table->dateTime('date');
            $table->int('count');

            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop(Conductor::TEST_TABLE_NAME);
    }
}
