<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateEnterprisesTable implements Migration
{
    public static function up(): void
    {
        Schema::create('enterprises', function (TableBuilder $table) {
            $table->varchar('address',225);
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('name',125);
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('enterprises');
    }
}
