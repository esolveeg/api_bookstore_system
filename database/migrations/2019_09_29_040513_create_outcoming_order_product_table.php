<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcomingOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcoming_order_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('outcoming_order_id')->unsigned();
            $table->unsignedInteger('qty')->length(5)->default(1);
            $table->foreign('outcoming_order_id')->references('id')
                  ->on('outcoming_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')
                  ->on('products')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('out_coming_order_products');
    }
}
