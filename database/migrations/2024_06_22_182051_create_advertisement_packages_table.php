<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementPackagesTable extends Migration
{
    public function up()
    {
        Schema::create('advertisement_packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('duration');
            $table->integer('size');
            $table->json('route_json');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('advertisement_packages');
    }
}
