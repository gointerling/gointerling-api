<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rel_merchant_skill', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('merchant_id');
            $table->uuid('skill_id');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('skill_id')->references('id')->on('skills');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rel_merchant_skill');
    }
};
