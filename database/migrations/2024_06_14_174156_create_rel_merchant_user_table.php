<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rel_merchant_user', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('merchant_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rel_merchant_user');
    }
};
