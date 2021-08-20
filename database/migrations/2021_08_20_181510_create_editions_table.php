<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('year_published');
            $table->text('link');

            // edition has one editor
            $table->unsignedBigInteger('editor_id');
            $table->foreign('editor_id')->references('id')->on('editors');

            // edition has one publisher
            $table->unsignedBigInteger('publisher_id');
            $table->foreign('publisher_id')->references('id')->on('publishers');

            // edition has one piece
            $table->unsignedBigInteger('piece_id');
            $table->foreign('piece_id')->references('id')->on('pieces');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('editions');
    }
}
