<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAgencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency',function(Blueprint $table){
            $table->decimal('lat',10,6)->nullabe();
            $table->decimal('lng',10,6)->nullabe();
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
        Schema::table('agency',function(Blueprint $table){
           $table->dropColumn(['lat','lng']);
        });
    }
}
