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
//        Schema::create('agent_type', function (Blueprint $table) {
//            $table->id();
//            $table->string('name', 50);
//        });
//
//        Schema::create('agents', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('type_id')->constrained('agent_type');
//            $table->string('name', 100);
//            $table->string('address', 100);
//            $table->string('email')->unique();
//            $table->foreignId('user_id')->constrained('users');
//            $table->timestamps();
//        });


        DB::statement("
            create table agent_type
            (
                id   bigserial      primary key,
                name varchar(50)    not null    unique
            );
        ");


        DB::statement("
            create table agents
            (
                id         bigserial    primary key,
                type_id    bigint       not null    references agent_type,
                name       varchar(100) not null,
                address    varchar(100) not null,
                email      varchar(255) not null    unique,
                user_id    bigint       not null    references users    unique,
                created_at timestamp(0),
                updated_at timestamp(0)
            );
        ");

        DB::statement("
            create or replace function count_accepted_contracts(id_val int)
                returns int as $$
            declare
                accepted_count int;
            begin
                select count(*) into accepted_count
                from contracts
                where agent_id = id_val and status = 'accepted';

                return accepted_count;
            end;
            $$ language plpgsql;
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
        Schema::dropIfExists('agent_type');
        DB::statement("drop function if exists count_accepted_contracts(int)");
    }
};
