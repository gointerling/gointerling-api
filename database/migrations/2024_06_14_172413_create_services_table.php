<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->bigInteger('price');
            $table->enum('type', ['standard', 'premium']);
            $table->text('time_estimated');
            $table->text('time_estimated_unit');
            $table->text('desc')->nullable();
            $table->json('language_sources')->nullable();
            $table->json('language_destinations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}
