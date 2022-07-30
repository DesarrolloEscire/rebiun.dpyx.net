<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Controller;
use App\Models\Evaluator as Evaluators;
use App\Models\Repository;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Notifications\SendMailNotificationStartEvaluation as SendStartEvaluation;
use App\Http\Controllers\Notifications\SendMailSelectedRepository;
use Illuminate\Support\Facades\Auth;

class AttachEvaluatorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Repository $repository)
    {
        $evaluator = Auth::user()->evaluators()->first();

        //if the repository has exceeded the evaluator limit send email and message
        if ($repository->evaluators->count() >= 2) {
            Alert::warning('Lo sentimos alguien más ya tomo este puesto');
            return redirect()->route('repositories.chooserepositories.index');
        }

        // if there are not any evaluator, send mail
        if ($repository->evaluators->count() == 0) {
            (new SendStartEvaluation($repository))->SendMailNotification();
        }

        // attach the evaluator to the repository
        $repository->evaluators()->attach($evaluator->id);

        // Notify the evaluator through mail
        (new SendMailSelectedRepository($evaluator, $repository))->SendMailNotification();


        Alert::success('Evaluador agregado');
        return redirect()->route('repositories.chooserepositories.index');
    }
}
