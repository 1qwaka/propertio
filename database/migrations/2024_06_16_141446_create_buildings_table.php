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
//        Schema::create('building_type', function (Blueprint $table) {
//            $table->id();
//            $table->string('name', 50);
//        });
//
//        Schema::create('buildings', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('type_id')->constrained('building_type');
//            $table->boolean('hot_water')->default(true);
//            $table->boolean('gas')->default(true);
//            $table->unsignedSmallInteger('elevators')->default(0);
//            $table->unsignedSmallInteger('floors');
//            $table->unsignedSmallInteger('build_year');
//            $table->foreignId('district_id')->constrained('districts');
//            $table->foreignId('developer_id')->constrained('developers');
//            $table->string('address', 100);
//            $table->timestamps();
//        });


        DB::statement("
            create table building_type
            (
                id   bigserial      primary key,
                name varchar(50)    not null    unique
            );
        ");

        DB::statement("
            create table buildings
            (
                id           bigserial                      primary key,
                type_id      bigint                         not null    references building_type,
                hot_water    boolean  default true          ,
                gas          boolean  default true          ,
                elevators    smallint default 0
                        constraint check_elevators check ( elevators >= 0 ),
                floors       smallint                       not null
                        constraint check_floors check ( floors >= 0 ),
                build_year   smallint                       not null,
                developer_id bigint                         references developers on delete set null,
                address      varchar(100)                   not null,
                created_at   timestamp(0),
                updated_at   timestamp(0)
            );
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('building_type');
    }
};
