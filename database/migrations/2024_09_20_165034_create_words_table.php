<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsTable extends Migration
{
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('english_word');
            $table->string('pronunciation')->nullable();
            $table->string('word_type');
            $table->string('polish_word');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('words');
    }
}
