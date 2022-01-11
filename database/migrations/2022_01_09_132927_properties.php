<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Properties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            $table->string('reference')->unique();
            $table->string('usageType');
            $table->string('region');
            $table->string('town');
            $table->string('street');
            $table->string('number');
            $table->string('stair')->nullable();
            $table->string('floor')->nullable();
            $table->string('door')->nullable();

            $table->timestamp('assigned_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
