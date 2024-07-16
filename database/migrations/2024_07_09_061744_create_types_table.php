<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->string('type_name', 255)->nullable();
            $table->string('type_name_hi', 255)->charset('utf8');
            $table->string('del_mrp', 255);
            $table->string('mrp', 255)->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('gst_percentage', 255);
            $table->string('gst_percentage_price', 255);
            $table->string('selling_price', 255)->nullable();
            $table->string('weight',50);
            $table->string('rate', 255);
            $table->string('ip', 255)->nullable();
            $table->string('date', 255)->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->integer('is_active')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('ecom_products')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('ecom_categories')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types');
    }
}
