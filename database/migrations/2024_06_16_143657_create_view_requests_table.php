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
//        Schema::create('view_requests', function (Blueprint $table) {
//            $table->id();
//            $table->enum('status', ['open', 'accepted', 'rejected']);
//            $table->date('date')->nullable();
//            $table->foreignId('property_id')->constrained('properties');
//            $table->foreignId('user_id')->constrained('users');
//            $table->timestamps();
//        });

        DB::statement("
            create type view_request_status_t as enum
            (
                'open',
                'accepted',
                'rejected'
            );
        ");

        DB::statement("
            create table view_requests
            (
                id          bigserial               primary key,
                status      view_request_status_t   not null,
                date        date,
                property_id bigint       not null   references properties on delete cascade ,
                user_id     bigint       not null   references users on delete cascade,
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
        Schema::dropIfExists('view_requests');
        DB::statement("drop type view_request_status_t");
    }
};
