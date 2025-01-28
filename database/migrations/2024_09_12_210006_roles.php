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
//            DB::statement("create role guest;");
//            DB::statement("create role agent;");
//            DB::statement("create role common_user;");
//            DB::statement("create role administrator;");
//            DB::statement("grant select on advertisements, contracts, properties to guest;");
//            DB::statement("grant insert on users to guest;");
//
//            DB::statement("grant select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type to common_user;");
//            DB::statement("grant insert on agents, view_requests to common_user;");
//            DB::statement("grant delete on view_requests to common_user;");
//            DB::statement("grant update on contracts, users to common_user;");
//
//            DB::statement("grant select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type to agent;");
//            DB::statement("grant insert, delete on contracts, properties, advertisements to agent;");
//            DB::statement("grant update on agents, view_requests, contracts, properties, advertisements to agent;");
//
//            DB::statement("grant select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type to administrator;");
//            DB::statement("grant insert, update, delete on buildings, cities, developers, districts to administrator;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//            DB::statement("revoke select on advertisements, contracts, properties from guest;");
//            DB::statement("revoke insert on users from guest;");
//
//            DB::statement("revoke select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type from common_user;");
//            DB::statement("revoke insert on agents, view_requests from common_user;");
//            DB::statement("revoke delete on view_requests from common_user;");
//            DB::statement("revoke update on contracts, users from common_user;");
//
//            DB::statement("revoke select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type from agent;");
//            DB::statement("revoke insert, delete on contracts, properties, advertisements from agent;");
//            DB::statement("revoke update on agents, view_requests, contracts, properties, advertisements from agent;");
//
//            DB::statement("revoke select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type from administrator;");
//            DB::statement("revoke insert, update, delete on buildings, cities, developers, districts from administrator;");
//
//            DB::statement("drop role if exists guest;");
//            DB::statement("drop role if exists agent;");
//            DB::statement("drop role if exists common_user;");
//            DB::statement("drop role if exists administrator;");
    }
};
