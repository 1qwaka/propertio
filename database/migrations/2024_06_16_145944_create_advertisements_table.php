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
//        Schema::create('advertisements', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('agent_id')->constrained('agents');
//            $table->longText('description');
//            $table->unsignedInteger('price');
//            $table->foreignId('property_id')->constrained('properties');
//            $table->enum('type', ['sale', 'rent']);
//            $table->boolean('hidden')->default(false);
//            $table->timestamps();
//        });



        DB::statement("
            DO $$ BEGIN
                create type advertisement_type_t as enum
                (
                    'sell',
                    'rent'
                );
            EXCEPTION
                WHEN duplicate_object THEN null;
            END $$;
        ");

        DB::statement("
            create table advertisements
            (
                id          bigserial               primary key,
                agent_id    bigint                  not null        references agents on delete cascade,
                description text                    ,
                price       integer                 not null
                        constraint check_price check ( price > 0 ),
                property_id bigint                  not null        references properties on delete cascade,
                type        advertisement_type_t    not null,
                hidden      boolean default false   ,
                created_at  timestamp(0),
                updated_at  timestamp(0)
            );
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
        DB::statement("drop type advertisement_type_t");
    }
};
