<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        Schema::create('districts', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('city_id')->constrained('cities');
//            $table->unsignedInteger('population');
//            $table->unsignedInteger('area');
//            $table->string('name', 100);
//            $table->float('rating');
//            $table->timestamps();
//        });

//        DB::statement("
//            create table districts
//            (
//                id         bigserial        primary key,
//                city_id    bigint           references cities on delete set null,
//                population integer          ,
//                area       integer          ,
//                name       varchar(100)     not null,
//                rating     float            ,
//                created_at timestamp(0),
//                updated_at timestamp(0)
//            );
//        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('districts');
    }
};
