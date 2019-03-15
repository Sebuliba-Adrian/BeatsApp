<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'comments',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('comment');
                $table->unsignedInteger('track_id');
                $table->unsignedInteger('user_id');
                $table->foreign('track_id')->references('id')
                    ->on('tracks')->onDelete('cascade');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
