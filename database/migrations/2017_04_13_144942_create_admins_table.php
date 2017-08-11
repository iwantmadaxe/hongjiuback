<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('admins', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->string('name');
//            $table->string('poster');
//            $table->string('phone',20);
//            $table->string('password',60);
//            $table->string('email',255)->index();
//            $table->tinyInteger('is_first')->unsigned()->default(0);
//            $table->dateTime('created_at');
//            $table->dateTime('updated_at');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('admins');
    }
}
