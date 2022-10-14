<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('item_type_id');
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            $table->foreign('item_type_id')->references('id')->on('item_types')->cascadeOnDelete();
            $table->string('sales_channel');
            $table->string('order_priority');
            $table->date('order_date');
            $table->string('order_id');
            $table->date('ship_date');
            $table->decimal('units_sold', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_revenue', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('total_profit', 10, 2);
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
        Schema::dropIfExists('orders');
    }
};