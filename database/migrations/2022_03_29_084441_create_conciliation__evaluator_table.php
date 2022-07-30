<?php

use App\Models\Conciliation;
use App\Models\Evaluator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConciliationEvaluatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $conciliations = Conciliation::get();

        Schema::create('conciliation__evaluator', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conciliation_id');
            $table->unsignedBigInteger('evaluator_id');

            $table
                ->foreign('conciliation_id')
                ->references('id')
                ->on('conciliations')
                ->onDelete('cascade');

            $table
                ->foreign('evaluator_id')
                ->references('id')
                ->on('evaluators')
                ->onDelete('cascade');
        });

        $conciliations->map(function ($conciliation) {

            $evaluatorIds = explode(',', $conciliation->evaluators_ids);

            $evaluators = Evaluator::whereIn('id',$evaluatorIds)->get();

            $evaluators->map( function($evaluator) use($conciliation){
                DB::table('conciliation__evaluator')->insert([
                    'conciliation_id' => $conciliation->id,
                    'evaluator_id' => $evaluator->id
                ]);

            } );
        });

        Schema::table('conciliations', function (Blueprint $table) {
            $table->dropColumn('evaluators_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conciliation__evaluator');

        Schema::table('conciliations', function (Blueprint $table) {
            $table->text('evaluators_ids');
        });
    }
}
