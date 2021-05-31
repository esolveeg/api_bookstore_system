<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name' , 100);
            $table->string('email' , 200)->nullable();
            $table->string('address')->nullable();
            $table->string('phone' , 16)->nullable();
            $table->unsignedInteger('rent')->length(5)->default(0);
            $table->unsignedInteger('bills')->length(5)->default(0);
            $table->integer('balance')->length(5)->nullable();
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
        Schema::dropIfExists('branches');
    }
}
