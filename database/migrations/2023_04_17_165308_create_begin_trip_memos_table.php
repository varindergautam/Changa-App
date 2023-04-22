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
        Schema::create('begin_trip_memos', function (Blueprint $table) {
            $table->id();
            $table->integer('begin_tripe_id');
            $table->string('voice_memo')->nullable();
            $table->integer('guide_id')->nullable();
            $table->string('time_of_recording')->nullable();
            $table->enum('file_type',[0,1,2])->comment('0 for audio, 1 for video, 2 for image')->nullable();
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
        Schema::dropIfExists('begin_trip_memos');
    }
};
