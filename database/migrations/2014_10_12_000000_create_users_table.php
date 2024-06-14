<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('password');
            $table->text('photo')->nullable();
            $table->text('address')->nullable();
            $table->text('credential_id')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->enum('status', ['active', 'verified', 'pending', 'inactive']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
