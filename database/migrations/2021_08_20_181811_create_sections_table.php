<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('order');
            $table->string('tempo_text')->nullable();
            $table->enum('mm_note', [1, 2, 4, 8, 16, 32, 64, 128])->nullable();
            $table->boolean('mm_note_dotted')->default(false);
            $table->unsignedSmallInteger('bpm')->nullable();

            $table->enum('structural_note', [1, 2, 4, 8, 16, 32, 64, 128])->nullable();
            $table->boolean('structural_note_dotted')->default(false);
            $table->enum('stacatto_note', [1, 2, 4, 8, 16, 32, 64, 128])->nullable();
            $table->boolean('stacatto_note_dotted')->default(false);
            $table->enum('ornamental_note', [1, 2, 4, 8, 16, 32, 64, 128])->nullable();
            $table->boolean('ornamental_note_dotted')->default(false);

            // section belongs to one time signature
            $table->unsignedBigInteger('movement_id');
            $table->foreign('movement_id')->references('id')->on('movements');

            // section belongs to one edition
            $table->unsignedBigInteger('edition_id');
            $table->foreign('edition_id')->references('id')->on('editions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
