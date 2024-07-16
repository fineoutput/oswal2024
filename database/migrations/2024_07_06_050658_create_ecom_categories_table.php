<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcomCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecom_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('short_disc', 300)->nullable();
            $table->string('long_desc' , 1000)->nullable();
            $table->integer('sequence')->nullable();
            $table->string('name_hi', 255)->charset('utf8');
            $table->string('short_disc_hi', 255)->charset('utf8');
            $table->string('long_desc_hi', 255)->charset('utf8');
            $table->string('url', 200)->nullable();
            $table->string('image', 200)->nullable();
            $table->string('app_image', 255)->nullable();
            $table->string('slide_img1', 100)->nullable();
            $table->string('slide_img2', 100)->nullable();
            $table->string('slide_img3', 100)->nullable();
            $table->integer('is_active')->nullable();
            $table->string('ip', 100)->nullable();
            $table->integer('added_by')->nullable();
            $table->string('cur_date', 100);
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
        Schema::dropIfExists('ecom_categories');
    }
}
