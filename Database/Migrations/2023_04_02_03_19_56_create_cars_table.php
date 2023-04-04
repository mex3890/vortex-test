<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateCarsTable extends Migration
{
    public static function up(): void
    {
        Schema::create('cars', function (TableBuilder $table) {
            $table->id()->primaryKey()->autoIncrement();
            $table->varchar('name', 125);
            $table->varchar('brand', 125);
            $table->id('user_id')->unique()->foreignKey('users', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('cars');
    }
}
