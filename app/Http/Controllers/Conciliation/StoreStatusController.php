<?php

namespace App\Http\Controllers\Conciliation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repository;
use App\Models\Evaluator;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Repositories\SendRepositoryController;

class StoreStatusController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Repository $repository)
    {
        if (!$repository->conciliation->is_check_list_complete) {
            Alert::warning('¡Lo sentimos necesita estar llena la lista de conciliacion!');
            return redirect()->back();
        }

        $authEvaluator = Evaluator::firstWhere('evaluator_id', '=', Auth::user()->id);

        $statusResolution = $repository
            ->evaluation
            ->statusResolutions()
            ->whereEvaluator($authEvaluator)
            ->first();

        $statusResolution
            ->changeStatus($request->response);

        $statusResolution
            ->asClosed();

        if ($request->comment_body) {
            $repository->conciliation->comments()->create([
                'body' => $request->comment_body,
                'user_id' => Auth::user()->id
            ]);
        }

        (new SendRepositoryController())
            ->CompareStatus($repository, $request->response);


        Alert::success('¡Has enviado tu respuesta!');
        return redirect()->route('repositories.index');
    }
}
