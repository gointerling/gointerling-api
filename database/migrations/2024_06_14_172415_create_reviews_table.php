<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('rating');
            $table->text('review_message')->nullable();
            $table->uuid('order_id');
            $table->uuid('merchant_user_id');
            $table->uuid('reviewer_id');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('merchant_user_id')->references('id')->on('users');
            $table->foreign('reviewer_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
