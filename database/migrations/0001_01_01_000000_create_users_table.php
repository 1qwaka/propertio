<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        Schema::create('users', function (Blueprint $table) {
//            $table->id();
//            $table->string('name')->nullable();
//            $table->string('email')->unique();
//            $table->string('password');
//            $table->boolean('is_admin');
//            $table->rememberToken();
//            $table->timestamps();
//        });
//
//        Schema::create('sessions', function (Blueprint $table) {
//            $table->string('id')->primary();
//            $table->foreignId('user_id')->nullable();
//            $table->string('ip_address', 45)->nullable();
//            $table->text('user_agent')->nullable();
//            $table->longText('payload');
//            $table->integer('last_activity');
//        });

    DB::statement("
        create table users
        (
            id             bigserial    primary key,
            name           varchar(100),
            email          varchar(100) not null        unique,
            password       varchar(255) not null,
            is_admin       boolean      default false   not null,
            remember_token varchar(100),
            created_at     timestamp(0),
            updated_at     timestamp(0)
        );
    ");

    DB::statement("
        create table sessions
        (
            id            varchar(255) not null primary key,
            user_id       bigint,
            ip_address    varchar(45),
            user_agent    text,
            payload       text         not null,
            last_activity integer      not null
        );
    ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
