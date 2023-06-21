<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateCarsTable implements Migration
{
    public static function up(): void
    {
        Schema::create('cars', function (TableBuilder $table) {
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('name',125);
            $table->varchar('brand',125);
            $table->bigInt('user_id')->foreignKey('users', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('cars');
    }
}
