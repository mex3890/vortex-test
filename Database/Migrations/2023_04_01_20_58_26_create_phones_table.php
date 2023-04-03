<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreatePhonesTable extends Migration
{
    public static function up(): void
    {
        Schema::create('phones', function (TableBuilder $table) {
            $table->id()->primaryKey()->autoIncrement();
            $table->varchar('number', 30);
            $table->id('user_id')->foreignKey('users', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('phones');
    }
}
