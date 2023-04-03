<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateUsersTable extends Migration
{
    public static function up(): void
    {
        Schema::create('users', function (TableBuilder $table) {
            $table->id()->primaryKey()->autoIncrement();
            $table->varchar('name', 225);
            $table->varchar('email', 125)->unique();
            $table->varchar('password', 125);
            $table->dateTime('created_at');
            $table->id('enterprise_id')->foreignKey('enterprises', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('users');
    }
}
