<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\EvaluationFinishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RequestEvaluatorListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // $evaluators = User::evaluators()->get();

        // foreach ($evaluators as $evaluator) {
        //     $evaluator->notify(new EvaluationFinishedNotification($event->evaluation));
        // }


        $evaluators = $event->evaluation->repository->evaluators;

            dd($evaluators);
        //$evaluator->notify(new EvaluationFinishedNotification($event->evaluation));


    }
}
