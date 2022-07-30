<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricalRepositoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_repository', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historical_id');
            $table->unsignedBigInteger('repository_id');

            $table
                ->foreign('historical_id')
                ->references('id')
                ->on('historicals')
                ->onDelete('cascade');

            $table
                ->foreign('repository_id')
                ->references('id')
                ->on('repositories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historical_repository');
    }
}
