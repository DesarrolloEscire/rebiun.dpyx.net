<?php

namespace App\Http\Controllers\Repositories;


use App\Http\Controllers\Controller;
use App\Jobs\Repositories\ReviewedMail;
use App\Mail\ConciliationDisagreementMail;
use App\Mail\NewConciliationMail;
use App\Models\AnswerHistory;
use App\Models\Evaluation;
use App\Models\Evaluator;
use App\Models\EvaluationHistory;
use App\Models\ObservationHistory;
use App\Models\Repository;
use App\Synchronizers\AnswerSynchronizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\StatusResolution;
use App\Models\Conciliation;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendRepositoryController extends Controller
{
    public function __invoke(Repository $repository, Request $request)
    {
        if ($request->status == null) {
            Alert::warning('Debes seleccionar un Status');
            return redirect()->back(409);
        }

        if ($request->comments) {
            $comment = Comment::create([
                'user_id' => Auth::user()->id,
                'body' => $request->comments
            ]);

            $repository->evaluation->comments()->attach($comment);
        }

        $this->handleRepositoryStatus($repository, $request->status);

        if ($repository->has_observations) {
            $this->generateHistory($repository->evaluation);
        }

        $this->handleEvaluationStatus($repository, $request->status);

        foreach ($repository->evaluation->answers as $answer) {
            (new AnswerSynchronizer($answer))->execute();
        }

        Alert::success(__("messages.Controllers.Repositories.SendRepositoryController.AlertSuccess"));
        return redirect()->route('dashboard');
    }

    private function generateHistory(Evaluation $evaluation)
    {
        $evaluationHistory = EvaluationHistory::create([
            'repository_id' => $evaluation->repository_id,
            'evaluator_id' => Auth::user()->id,
            'status' => $evaluation->status
        ]);

        foreach ($evaluation->answers as $answer) {
            $answerHistory = AnswerHistory::create([
                'choice_id' => $answer->choice_id,
                'question_id' => $answer->question_id,
                'evaluation_history_id' => $evaluationHistory->id,
                'description' => $answer->description
            ]);


            if ($answer->observation) {
                ObservationHistory::create([
                    'answer_history_id' => $answerHistory->id,
                    'description' => $answer->observation->description,
                    'files_paths' => $answer->observation->files_paths
                ]);
            }
        }
    }

    private function handleEvaluationStatus($repository, $status)
    {
        $statusResolutions = $repository->evaluation->statusResolutions;

        if ($statusResolutions->count() <= 1) {
            return;
        }

        $statusResults = $statusResolutions->pluck('status')->toArray();

        if ($statusResults[0] != $statusResults[1]) {
            return;
        }

        $repository->evaluation->asReviewed();

        ReviewedMail::dispatch($repository);
    }

    private function handleRepositoryStatus($repository, $status)
    {
        $evaluator_id = $this->getEvaluatorId();

        StatusResolution::create([
            'evaluator_id' => $evaluator_id,
            'evaluation_id' => $repository->evaluation->id,
            'status' => $status
        ]);

        //TODO not save just until it have 2 evaluators emited resolution
        // status resolution of the repository
        $statusResolutions = $repository->evaluation->statusResolutions;

        if ($statusResolutions->count() <= 1) {
            return;
        }

        // retrieve array of status
        $statusResults = $statusResolutions
            ->pluck('status')
            ->toArray();

        // if first status is the same than the second, update repository status
        if ($statusResults[0] === $statusResults[1]) {
            $repository->changeStatus($status);
            return;
        }

        // [X]
        $categories = Category::all();
        $category_check = [];
        foreach ($categories as $category) {
            $category_check[0][] = 0;
            $category_check[1][] = 0;
        }

        // get all evaluators ids of the repository and [X]
        $evaluators_ids = [];
        foreach ($repository->evaluators as $i => $evaluator) {
            $evaluators_ids[] = $evaluator->id;

            array_unshift($category_check[$i], $evaluator->id);
        }


        // create new conciliation
        $conciliation = Conciliation::create([
            'check_list' => json_encode($category_check),
            'repository_id' => $repository->id,
        ]);

        $conciliation->evaluators()->sync($evaluators_ids);

        $userToBeNotifiedOfConciliation = User::administrators()->get()->pluck('email');
        $userToBeNotifiedOfConciliation = $userToBeNotifiedOfConciliation->merge($conciliation->evaluators()->get()->pluck('asUser.email'));
        $userToBeNotifiedOfConciliation = $userToBeNotifiedOfConciliation->merge($repository->responsible->email);

        Mail::to($userToBeNotifiedOfConciliation->filter())->send(new NewConciliationMail($conciliation));

        // open all status resolutions retrieved
        foreach ($statusResolutions as $statusResolution) {
            $statusResolution->status_conciliation = "open";
            $statusResolution->save();
        }

        // update conciliation to the repository
        $repository->conciliation_id = $conciliation->id;
        $repository->save();
    }


    private function getEvaluatorId()
    {
        $evaluator = Evaluator::where('evaluator_id', Auth::user()->id)->first();
        return $evaluator->id;
    }


    /**
     * 
     * 
     */
    public function CompareStatus($repository, $status)
    {
        $statusResolutions = $repository->evaluation->statusResolutions;

        $conciliationStatus = $statusResolutions
            ->pluck('status_conciliation')
            ->toArray();

        // if any evaluator have still their conciliations opened, finish the process
        if ($conciliationStatus[0] != 'close' || $conciliationStatus[1] != 'close') {
            return;
        }

        $statusResults = $statusResolutions
            ->pluck('status')
            ->toArray();

        // dd($statusResults[0], $statusResults[1]);

        // if there are difference, mark resolution as opened
        if ($statusResults[0] != $statusResults[1]) {
            foreach ($statusResolutions as $statusResolution) {
                $statusResolution
                    ->asOpened();
            }

            Mail::to($this->emailsForUnconciliatedNotification($repository))
                ->send(
                    new ConciliationDisagreementMail(
                        $repository->conciliation
                    )
                );

            return;
        }

        // if there are not difference, change repository status
        $repository
            ->changeStatus($status);

        // mark repository as reviewed
        $repository
            ->evaluation
            ->asReviewed();

        // send mail
        ReviewedMail::dispatch($repository);
    }

    public function emailsForUnconciliatedNotification(Repository $repository)
    {
        // dd($repository
        //     ->evaluators
        //     ->pluck('evaluator_id')
        //     ->toArray());

        $evaluatorsEmails = User::whereIn(
            'id',
            $repository
                ->evaluators
                ->pluck('evaluator_id')
                ->toArray()
        )->get()->pluck('email');

        $adminEmails = User::administrators()->get()->pluck('email');
        return collect($evaluatorsEmails)->merge($adminEmails);
    }
}
