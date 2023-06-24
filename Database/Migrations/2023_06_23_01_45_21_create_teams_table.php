<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateTeamsTable implements Migration
{
    public static function up(): void
    {
        Schema::create('teams', function (TableBuilder $table) {
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('name',125);
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('teams');
    }
}
