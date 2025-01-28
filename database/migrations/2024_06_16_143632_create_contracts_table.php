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
//        Schema::create('contracts', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('property_id')->constrained('properties');
//            $table->enum('status', ['open', 'accepted', 'rejected']);
//            $table->date('date')->nullable();
//            $table->unsignedInteger('price');
//            $table->foreignId('buyer_id')->constrained('users');
//            $table->foreignId('agent_id')->constrained('agents');
//            $table->date('until')->nullable();
//            $table->boolean('buyer_agreement')->default(false);
//            $table->timestamps();
//        });

//        DB::statement("
//            create type contract_status_t as enum
//            (
//                'open',
//                'accepted',
//                'rejected'
//            );
//        ");
//
//        DB::statement("
//            create table contracts
//            (
//                id              bigserial               primary key,
//                property_id     bigint                                  references properties on delete set null,
//                status          contract_status_t       not null,
//                date            date default now()      not null,
//                price           integer                 not null
//                        constraint check_price check ( price > 0 ),
//                buyer_id        bigint                                  references users on delete set null,
//                agent_id        bigint                                  references agents on delete set null,
//                until           date,
//                buyer_agreement boolean default false   not null,
//                created_at      timestamp(0),
//                updated_at      timestamp(0)
//            );
//        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('contracts');
//        DB::statement("drop type contract_status_t");
    }
};
