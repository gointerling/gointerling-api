<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('price');
            $table->uuid('service_id');
            $table->uuid('merchant_id');
            $table->uuid('merchant_user_id');
            $table->dateTime('estimated_date');
            $table->uuid('user_id');
            $table->text('user_file_url')->nullable();
            $table->json('comment_json')->nullable();
            $table->text('meet_url')->nullable();
            $table->enum('order_status', ['pending', 'paid', 'refund', 'complete', 'failed']);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('merchant_user_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
