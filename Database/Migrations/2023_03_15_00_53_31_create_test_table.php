<?php

namespace Database\Migrations;

use App\Tests\Conductor;
use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateTestTable implements Migration
{
    public static function up(): void
    {
        Schema::create(Conductor::TEST_TABLE_NAME, function (TableBuilder $table) {
            $table->id()->primaryKey()->autoIncrement();
            $table->varchar('name', 255);
            $table->float('operation_full_time_in_milliseconds');
            $table->float('average_in_milliseconds');
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
