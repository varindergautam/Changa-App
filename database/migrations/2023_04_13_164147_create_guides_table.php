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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->integer('guide_id')->nullable();
            $table->integer('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('file_type',[0,1,2])->comment('0 for audio, 1 for video, 2 for image')->nullable();
            $table->string('file')->nullable();
            $table->string('background_image')->nullable();
            $table->enum('active',[0,1])->default(1)->comment('0 for InActive, 1 for Active');
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
        Schema::dropIfExists('guides');
    }
};
