<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_subs', function (Blueprint $table) {
            $table->id();
            $table->decimal('start_range', 10, 2);
            $table->decimal('end_range', 10, 2);
            $table->decimal('mrp', 10, 2);
            $table->decimal('gst_percentage', 5, 2);
            $table->decimal('gst_percentage_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2);
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('rate', 10, 2);
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
        Schema::dropIfExists('types_subs');
    }
}
