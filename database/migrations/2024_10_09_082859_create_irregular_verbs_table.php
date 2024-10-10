<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    Schema::create('irregular_verbs', function (Blueprint $table) {
        $table->id();
        $table->string('verb_1st_form');
        $table->string('verb_2nd_form');
        $table->string('verb_3rd_form');
        $table->string('polish_translation');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('irregular_verbs');
}

};
