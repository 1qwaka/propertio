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
//        Schema::create('developers', function (Blueprint $table) {
//            $table->id();
//            $table->string('address', 100);
//            $table->string('name', 100);
//            $table->float('rating');
//            $table->string('email', 100)->unique();
//            $table->timestamps();
//        });

        DB::statement("
            create table developers
            (
                id         bigserial        primary key,
                address    varchar(100)     not null,
                name       varchar(100)     not null,
                rating     float            ,
                email      varchar(100)     not null    unique,
                created_at timestamp(0),
                updated_at timestamp(0)
            );
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
