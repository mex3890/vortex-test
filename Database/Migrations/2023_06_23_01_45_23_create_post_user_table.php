<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreatePostUserTable implements Migration
{
    public static function up(): void
    {
        Schema::create('post_user', function (TableBuilder $table) {
            $table->bigInt('id')->primaryKey()->autoIncrement();
            $table->bigInt('user_id')->foreignKey('users', 'id');
            $table->bigInt('post_id')->foreignKey('posts', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('post_user');
    }
}
