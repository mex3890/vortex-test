<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateTestsTable implements Migration
{
    public static function up(): void
    {
        Schema::create('tests', function (TableBuilder $table) {
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('name',255);
            $table->float('operation_full_time_in_milliseconds');
            $table->float('average_in_milliseconds');
            $table->dateTime('date');
            $table->int('count');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('tests');
    }
}
