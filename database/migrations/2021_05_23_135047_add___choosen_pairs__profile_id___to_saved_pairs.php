<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChoosenPairsProfileIdToSavedPairs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_pairs', function (Blueprint $table) {
            $table->boolean('choosen');
            $table->bigInteger('profile_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_pairs', function (Blueprint $table) {
            //
        });
    }
}
