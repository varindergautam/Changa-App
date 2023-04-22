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
        Schema::create('trip_journals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('text_1')->nullable();
            $table->text('text_2')->nullable();
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
        Schema::dropIfExists('trip_journals');
    }
};
