<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWineGrapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wine_grapes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->tinyInteger('wine_id')->default('1');
            $table->tinyInteger('grape_id')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('wine_grapes');
    }
}
