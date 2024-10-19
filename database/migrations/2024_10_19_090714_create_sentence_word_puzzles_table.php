<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentenceWordPuzzlesTable extends Migration
{
    public function up()
    {
        Schema::create('sentence_word_puzzles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id'); // Foreign key to the sections table
            $table->text('sentence'); // Store the sentence
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sentence_word_puzzles');
    }
}
