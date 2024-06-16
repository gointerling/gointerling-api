<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['translator', 'interpreter', 'both']);
            $table->text('bank_id');
            $table->string('bank');
            $table->string('bank_account');
            $table->string('cv_url')->nullable();
            $table->json('portfolios')->nullable();
            $table->json('certificates')->nullable();
            $table->enum('status', ['active', 'verified', 'pending', 'inactive']);
            $table->integer('rating')->default(0);
            $table->integer('recomended_count')->default(0);
            $table->boolean('is_first_time')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchants');
    }
}
