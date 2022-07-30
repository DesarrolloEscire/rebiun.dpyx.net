<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusResolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_resolutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluator_id');
            $table->unsignedBigInteger('evaluation_id');
            $table->text('status');
            $table->timestamps();

            $table->foreign('evaluator_id')->references('id')->on('evaluators')->onDelete('cascade');
            $table->foreign('evaluation_id')->references('id')->on('evaluations')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_resolutions');
    }
}
