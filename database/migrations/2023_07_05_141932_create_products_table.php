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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("category");
            $table->longText("description");
            $table->string('photo');
            $table->integer("price");
            $table->string("seller");
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('orders_id');
            $table->string("name");
            $table->string("email");
            $table->longText("address");
            $table->string("payment_id");
            $table->string("payment_status")->default('pending');
            $table->string("payment_date")->nullable();
            $table->string("payment_card_details")->nullable();
            $table->integer("total_amount");
            $table->timestamps();
        });


        Schema::create('orders_details', function (Blueprint $table) {
            $table->increments('orders_details_id');
            $table->integer("orders_id");
            $table->string("product_name");
            $table->integer("price");
            $table->integer("quantity");
            $table->integer("sub_amount");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
