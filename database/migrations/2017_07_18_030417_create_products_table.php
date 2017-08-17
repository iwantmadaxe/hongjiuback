<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->timestamps();
            $table->string('wine_name',50)->default('collinbr');
            $table->float('wine_price',12,2)->default('218.00');
            $table->integer('wine_net')->default('330');
            $table->string('taste_temperature',50)->default('25-35C');
            $table->tinyInteger('is_special')->default('1');
            $table->string('important',255)->default('yummy');
            $table->text('description');
            $table->tinyInteger('country')->default('1');
            $table->tinyInteger('district')->default('2');
            $table->tinyInteger('category')->default('1');
            $table->tinyInteger('grade')->default('2');
            $table->string('barcode',50)->default('987654321');
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
