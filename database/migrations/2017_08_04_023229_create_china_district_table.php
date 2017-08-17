<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChinaDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('china_districts', function (Blueprint $table) {
            $table->smallincrements('id');
            $table->string('name',270);
            $table->unsignedSmallInteger('parent_id');
            $table->char('initial',3);
            $table->string('initials',30);
            $table->string('pinyin',600);
            $table->string('suffix',15);
            $table->char('code',30);
            $table->tinyInteger('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('china_districts');
    }
}
