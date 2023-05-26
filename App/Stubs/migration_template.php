<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class MigrationClassName implements Migration
{
    public static function up(): void
    {
        Schema::create('$table_name', function (TableBuilder $table) {
            //$columns
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('$table_name');
    }
}
