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
            $table->varchar('email',125)->unique();
            $table->varchar('password',125);
            $table->dateTime('created_at');
            $table->bigInt('enterprise_id')->foreignKey('enterprises', 'id');
            $table->bigInt('enterprise_id')->cascadeOnDelete()->foreignKey('enterprises', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('users');
    }
}
