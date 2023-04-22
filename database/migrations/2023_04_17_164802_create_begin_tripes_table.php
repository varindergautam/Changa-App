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
        Schema::create('begin_tripes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('feeling_id')->nullable();
            $table->text('feeling_text')->nullable();
            $table->integer('intention_id')->nullable();
            $table->text('intention_text')->nullable();
            $table->string('type_of_trip')->nullable()->comment('like icons');
            $table->string('satisfaction_type')->nullable()->comment('light,moderate,deep');
            $table->text('satisfaction_text')->nullable();
            $table->integer('visual_id')->nullable();
            $table->integer('audio_tag_id')->nullable();
            $table->integer('audio_id')->nullable();
            $table->string('time_of_recording')->nullable();
            $table->timestamp('trip_start_date')->nullable();
            $table->timestamp('trip_end_date')->nullable();
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
        Schema::dropIfExists('begin_tripes');
    }
};
