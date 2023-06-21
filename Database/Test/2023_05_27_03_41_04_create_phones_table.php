<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreatePhonesTable implements Migration
{
    public static function up(): void
    {
        Schema::create('phones', function (TableBuilder $table) {
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('number',30);
            $table->bigInt('user_id')->unique();
            $table->bigInt('user_id')->unique()->foreignKey('users', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('phones');
    }
}
