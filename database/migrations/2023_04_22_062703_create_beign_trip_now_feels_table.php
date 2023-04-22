<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beign_trip_now_feels', function (Blueprint $table) {
            $table->id();
            $table->integer('beign_trip_id');
            $table->integer('feeling_id')->nullable();
            $table->string('feeling_text')->nullable();
            $table->string('refelct')->nullable();
            $table->string('trip_journal_emotion')->nullable();
            $table->text('trip_journal_emotion_text')->nullable();
            $table->string('trip_journal_insiahts')->nullable();
            $table->text('trip_journal_insiahts_text')->nullable();
            $table->string('trip_journal_vision')->nullable();
            $table->text('trip_journal_vision_text')->nullable();
            $table->string('trip_journal_unaudied')->nullable();
            $table->text('trip_journal_unaudied_text')->nullable();
            $table->string('different_tommorow')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beign_trip_now_feels');
    }
};
