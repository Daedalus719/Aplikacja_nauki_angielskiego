<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentenceRulesTable extends Migration
{
    public function up()
    {
        Schema::create('sentence_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->text('rule_text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sentence_rules');
    }
}
