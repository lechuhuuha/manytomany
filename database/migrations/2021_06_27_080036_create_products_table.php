<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('branch_id');
            $table->string('name', 60);
            $table->string('slug');
            $table->string('image', 200);
            $table->text('short_desc');
            $table->text('desc');
           $table->double('price');
           $table->unsignedInteger('selling')->default(1);
           $table->unsignedInteger('favourite')->default(0);// yêu thích
            $table->double('competitive_price');
            // $table->string('image', 200);
            $table->unsignedInteger('discount');
          
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
        Schema::dropIfExists('products');
    }
}
