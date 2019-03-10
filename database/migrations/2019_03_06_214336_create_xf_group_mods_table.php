<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXFGroupModsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xf_group_mods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mod_id')->unsigned();
            $table->foreign('mod_id')->references('id')->on('mods');
            $table->integer('xf_user_group_id');
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
        Schema::dropIfExists('group_mods');
    }
}
