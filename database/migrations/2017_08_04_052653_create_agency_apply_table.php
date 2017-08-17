<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgencyApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_apply', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone');
            $table->string('budget');
            $table->string('address');
            $table->smallInteger('province_id');
            $table->smallInteger('city_id');
            $table->smallInteger('district_id');
            $table->char('code',20)->nullable();
            $table->timestamps();
            $table->enum('status',['applying','passed','refused']);
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency_apply');
    }
}
