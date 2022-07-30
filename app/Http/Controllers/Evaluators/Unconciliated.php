<?php

namespace App\Http\Controllers\Evaluators;

use App\Http\Controllers\Controller;
use App\Models\Conciliation;
use App\Models\Evaluator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class Unconciliated extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $conciliation = Conciliation::firstWhere('repository_id', '=', $request->repository_id);

        if ($conciliation->evaluator_solve_id) {
            Alert::warning('!Lo sentimos alguien más ya tomo ese puesto¡');
            return redirect()->back();
        }

        $evaluator = Evaluator::firstWhere('evaluator_id', '=', $request->evaluator_id);
        $conciliation->evaluator_solve_id = $evaluator->id;
        $conciliation->save();

        Alert::success('¡Evaluador agregado!');
        return redirect()->back();
    }
}
