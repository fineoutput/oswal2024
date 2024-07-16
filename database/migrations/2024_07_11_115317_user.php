<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('first_name_hi', 255)->charset('utf8')->nullable();
            $table->string('device_id', 255)->nullable();
            $table->string('auth', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact', 50)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('status', 10)->nullable();
            $table->integer('is_hidden')->nullable();
            $table->string('ip', 255)->nullable();
            $table->string('date', 255)->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('is_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
