<?php

namespace Database\Migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class CreatePostsUsersTable extends Migration
{
    public static function up(): void
    {
        Schema::create('post_user', function (TableBuilder $table) {
            $table->id()->primaryKey()->autoIncrement();
            $table->id('post_id')->foreignKey('posts', 'id');
            $table->id('user_id')->foreignKey('users', 'id');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('post_user');
    }
}
