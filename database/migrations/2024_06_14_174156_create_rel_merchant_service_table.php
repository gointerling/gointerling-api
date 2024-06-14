<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rel_merchant_service', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('merchant_id');
            $table->uuid('service_id');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rel_merchant_service');
    }
};
