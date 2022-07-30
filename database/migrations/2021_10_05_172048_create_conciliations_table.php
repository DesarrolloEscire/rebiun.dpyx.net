<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliations', function (Blueprint $table) {
            $table->id();
            $table->text('check_list')->nullable();
            $table->text('evaluators_ids');
            $table->text('status')->nullable();
            $table->unsignedBigInteger('repository_id');
            $table->timestamps();

            $table->foreign('repository_id')
                ->references('id')
                ->on('repositories')->onUpdate('cascade') 
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
        Schema::dropIfExists('conciliations');
    }
}
