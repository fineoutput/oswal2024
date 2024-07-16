<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingChargesTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('city_id')->nullable();
            $table->string('weight1',);
            $table->string('shipping_charge1')->nullable();
            $table->string('weight2');
            $table->string('shipping_charge2');
            $table->string('weight3');
            $table->string('shipping_charge3');
            $table->string('weight4');
            $table->string('shipping_charge4');
            $table->string('weight5');
            $table->string('shipping_charge5');
            $table->string('weight6')->nullable();
            $table->string('shipping_charge6')->nullable();
            $table->string('ip')->nullable();
            $table->string('date')->nullable();
            $table->integer('added_by')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_charges');
    }
}
