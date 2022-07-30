<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Mail\EvaluationMail;
use App\Models\AnswerHistory;
use App\Models\Evaluation;
use App\Models\EvaluationHistory;
use App\Models\Evaluator;
use App\Models\User;
use App\PDFs\EvaluationPDF;
use App\Synchronizers\AnswerSynchronizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class SendEvaluationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Evaluation $evaluation)
    {
        $mandatoryDescriptionsWereNotAnswered = $evaluation
            ->answers()
            ->whereHas('choice', function ($query) {
                $query->wherePositivePunctuation();
            })->whereHas('question', function ($query) {
                return $query->where('has_description_field', 1);
            })->whereEmpty('description')
            ->exists();

        if ($mandatoryDescriptionsWereNotAnswered) {
            Alert::error('Tu evaluación no pudo ser enviada', 'Por favor, llena todas las preguntas que requieren descripción');
            return redirect()->back();
        }


        $evaluation->toInReview();


        $evaluationHistory = EvaluationHistory::create([
            'repository_id' => $evaluation->repository->id,
            'status' => $evaluation->status
        ]);


        $evaluation->repository->toInProgress();


        foreach ($evaluation->answers as $answer) {
            (new AnswerSynchronizer($answer))->execute();

            $answerHistory = new AnswerHistory;
            $answerHistory->choice_id = $answer->choice_id;
            $answerHistory->question_id = $answer->question_id;
            $answerHistory->evaluation_history_id = $evaluationHistory->id;
            $answerHistory->description = $answer->description;
            $answerHistory->save();
        }

        $emails = User::administrators()->get()->pluck('email');
        $emails = $emails->merge(Auth::user()->email );

        Mail::to($emails)->send(new EvaluationMail($evaluation));

        (new EvaluationPDF($evaluation))
            ->store();

        Alert::success('¡La evaluación ha sido enviada para su revisión!');
        return redirect()->route('repositories.index');
    }
}
