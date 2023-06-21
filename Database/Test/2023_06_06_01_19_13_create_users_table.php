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
            $table->dateTime('created_at');
            $table->varchar('email',125)->unique();
            $table->bigInt('enterprise_id')->cascadeOnDelete()->foreignKey('enterprises', 'id');
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('name',225);
            $table->varchar('password',125);
            $table->set('test',5);
            $table->set('test2',8);
            $table->enum('test3',4);
            $table->enum('test4',4)->default('2');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('users');
    }
}
