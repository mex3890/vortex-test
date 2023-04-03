<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreatePostsTable extends Migration
{
    public static function up(): void
    {
        Schema::create('posts', function (TableBuilder $table) {
            $table->id()->primaryKey()->autoIncrement();
            $table->varchar('name', 225);
            $table->dateTime('created_at');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('posts');
    }
}
