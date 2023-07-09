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
        Schema::dropIfExists('audio_tag_mutlis');
        Schema::create('audio_tag_mutlis', function (Blueprint $table) {
            $table->id();
            $table->integer('audio_tag_id');
            $table->integer('audio_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_tag_mutlis');
    }
};
