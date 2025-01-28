<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        Schema::create('floor_type', function (Blueprint $table) {
//            $table->id();
//            $table->string('name', 50);
//        });
//        Schema::create('property_condition', function (Blueprint $table) {
//            $table->id();
//            $table->string('name', 50);
//        });
//
//        Schema::create('properties', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('condition_id')->constrained('property_condition');
//            $table->foreignId('building_id')->constrained('buildings');
//            $table->smallInteger('floor');
//            $table->unsignedSmallInteger('area');
//            $table->foreignId('floor_type_id')->constrained('floor_type');
//            $table->string('address', 100);
//            $table->enum('living_space_type', ['primary', 'secondary']);
//            $table->foreignId('agent_id')->constrained('agents');
//            $table->timestamps();
//        });


//        DB::statement("
//            create table property_condition
//            (
//                id   bigserial      primary key,
//                name varchar(50)    not null
//            );
//        ");

        DB::statement("
            create table floor_type
            (
                id   bigserial      primary key,
                name varchar(50)    not null    unique
            );
        ");

        DB::statement("
            create type living_space_t as enum
            (
                'primary',
                'secondary'
            );
        ");


        DB::statement("
            create table properties
            (
                id                bigserial         primary key,
                renovation        varchar(100),
                building_id       bigint            references buildings,
                floor             smallint          not null,
                area              smallint
                        constraint check_area check ( area > 0 ),
                floor_type_id     bigint            not null    references floor_type,
                address           varchar(100)      not null,
                living_space_type living_space_t    not null,
                agent_id          bigint            not null    references agents on delete cascade,
                created_at        timestamp(0),
                updated_at        timestamp(0)
            );
        ");

        DB::statement("
           CREATE OR REPLACE FUNCTION check_floor() RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.floor > (SELECT floors FROM buildings WHERE id = NEW.building_id) THEN
                    RAISE EXCEPTION 'Floor cannot be higher than the number of floors in the building';
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::statement("
            CREATE TRIGGER trg_check_floor
            BEFORE INSERT OR UPDATE ON properties
            FOR EACH ROW EXECUTE FUNCTION check_floor();
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
        Schema::dropIfExists('floor_type');
        DB::statement('drop type living_space_t');
//        Schema::dropIfExists('living_space_t');
    }
};
