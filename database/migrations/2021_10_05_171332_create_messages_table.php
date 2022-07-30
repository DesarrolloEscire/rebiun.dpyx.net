<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('chat');
            $table->unsignedBigInteger('evaluator_id');

            $table->timestamps();


            $table->foreign('evaluator_id')->references('id')->on('evaluators')->onDelete('cascade');
          //  $table->foreign('conciliation_id')->references('id')->on('conciliations')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
