<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateUsersTable implements Migration
{
    public static function up(): void
    {
        Schema::create('users', function (TableBuilder $table) {
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('name',225);
            $table->bigInt('team_id')->foreignKey('teams', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('users');
    }
}
