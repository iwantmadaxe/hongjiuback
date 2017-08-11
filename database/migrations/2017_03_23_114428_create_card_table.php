<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//		Schema::create('cards', function (Blueprint $table) {
//			$table->increments('id');
//			$table->string('code');
//			$table->string('tel');
//			$table->string('iccid');
//			$table->string('acc_number');
//			$table->string('agent_id');
//			$table->string('telecom_id');
//			$table->tinyInteger('type');
//			$table->dateTime('created_at');
//			$table->dateTime('updated_at');
//		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//		Schema::dropIfExists('cards');
    }
}
