<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseWordTable extends Migration
{
    public function up()
    {
        Schema::create('course_word', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('word_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('word_id')->references('id')->on('words');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_word');
    }
}
