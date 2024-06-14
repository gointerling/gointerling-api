<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rel_merchant_package', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('merchant_id');
            $table->uuid('package_id');
            $table->timestamp('subscribe_at');
            $table->timestamp('valid_until');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rel_merchant_package');
    }
};
