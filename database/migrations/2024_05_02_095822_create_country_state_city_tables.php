<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('country_id');
            $table->string('country_name');
            $table->timestamps();
        });
        Schema::create('states', function (Blueprint $table) {
            $table->increments('state_id');
            $table->string('state_name');
            $table->integer('country_id');
            $table->timestamps();
        });
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('city_id');
            $table->string('city_name');
            $table->integer('state_id');
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
        Schema::drop('countries');
        Schema::drop('states');
        Schema::drop('cities');
    }

};
