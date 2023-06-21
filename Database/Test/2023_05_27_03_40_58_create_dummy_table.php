<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreateDummyTable implements Migration
{
    public static function up(): void
    {
        Schema::create('dummy', function (TableBuilder $table) {
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->varchar('fake_name',255);
            $table->float('money');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('dummy');
    }
}
