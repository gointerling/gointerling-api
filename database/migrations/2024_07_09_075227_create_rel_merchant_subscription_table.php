<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelMerchantSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_merchant_subscription', function (Blueprint $table) {
            $table->uuid('merchant_id');
            $table->uuid('package_id');
            $table->timestamp('subscribe_at');
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_trial')->default(true);
            $table->text('payment_file_url')->nullable();
            $table->timestamps();

            $table->primary(['merchant_id', 'package_id']);

            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('subscription_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_merchant_subscription');
    }
}
