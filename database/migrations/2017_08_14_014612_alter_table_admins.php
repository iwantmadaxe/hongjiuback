<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('admins',function (Blueprint $table){
        $sql = 'alter table `admins` modify `email` VARCHAR(255) default NULL';
        DB::statement($sql);
        $sql = 'alter table `admins` ADD UNIQUE (`name`)';
        DB::statement($sql);

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
    }
}
