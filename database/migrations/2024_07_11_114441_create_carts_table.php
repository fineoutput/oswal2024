<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('product_id');
            $table->string('type_id');
            $table->string('type_price');
            $table->string('quantity');
            $table->string('total_qty_price');
            $table->integer('checkout_status')->default(0);
            $table->string('cart_from')->nullable()->comment('1 for product details, 2 for trending, 3 for recent, 4 for productlist frag, 5 for search activity, 6 for search frag, 7 for moveToCart, 8 for cart page, 9 for product activity, 10 for feature products');
            $table->string('ip', 100)->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('ecom_categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('ecom_products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
