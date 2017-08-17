<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCultureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cultures',function (Blueprint $table){
           $table->smallInteger('type_id');
           $table->string('culture_title');
           $table->text('description');
           $table->string('culture_cover');
           $table->string('culture_url');
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
        Schema::table('culture',function (Blueprint $table){
           $table->dropColumn('type_id');
           $table->dropColumn('culture_title');
           $table->dropColumn('culture_desc');
           $table->dropColumn('culture_cover');
           $table->dropColumn('culture_url');
        });
    }
}
