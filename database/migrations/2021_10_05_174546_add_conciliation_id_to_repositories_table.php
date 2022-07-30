<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConciliationIdToRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repositories', function (Blueprint $table) {

            $table->unsignedBigInteger('conciliation_id')->nullable();
            $table->foreign('conciliation_id')->references('id')->on('conciliations')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repositories', function (Blueprint $table) {
            //
        });
    }
}
