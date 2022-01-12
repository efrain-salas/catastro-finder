<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AssignedPerUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('assigned_at');
        });

        Schema::create('property_user', function (Blueprint $table) {
            $table->foreignId('property_id');
            $table->foreignId('user_id');
            $table->timestamps();
            $table->primary(['property_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_user');

        Schema::table('properties', function (Blueprint $table) {
            $table->timestamp('assigned_at')->nullable();
        });
    }
}
