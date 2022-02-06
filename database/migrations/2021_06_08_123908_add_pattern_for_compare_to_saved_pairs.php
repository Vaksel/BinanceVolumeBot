<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatternForCompareToSavedPairs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_pairs', function (Blueprint $table) {
            $table->unsignedBigInteger('chosen_pattern')->default(1);

            $table->foreign('chosen_pattern')->references('id')->on('patterns_lists');
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
