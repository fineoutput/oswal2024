<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEcomProductsTable extends Migration
{
    public function up()
    {
        Schema::create('ecom_products', function (Blueprint $table) {
            $table->increments('id'); // This will create an unsigned integer primary key
            $table->unsignedInteger('category_id'); // Make sure this is unsigned
            $table->unsignedInteger('product_category_id'); // Make sure this is unsigned
            $table->string('name', 100);
            $table->text('long_desc')->nullable();
            $table->string('name_hi', 255)->charset('utf8');
            $table->text('long_desc_hi')->charset('utf8')->nullable();
            $table->string('url', 300);
            $table->string('hsn_code', 100)->nullable();
            $table->string('video', 255);
            $table->string('img1', 100);
            $table->string('img2', 100);
            $table->string('img3', 100);
            $table->string('img4', 255);
            $table->string('img_app1', 100);
            $table->string('img_app2', 100);
            $table->string('img_app3', 100);
            $table->string('img_app4', 100);
            $table->boolean('is_active');
            $table->string('is_cat_delete', 255)->default('0');
            $table->string('cur_date', 100);
            $table->string('ip', 100);
            $table->unsignedInteger('added_by'); // Assuming this references a user table
            $table->boolean('is_hot')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ecom_products');
    }
}
