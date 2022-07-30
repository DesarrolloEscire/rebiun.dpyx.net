<?php

namespace App\Observers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Repository;
use Illuminate\Support\Facades\Auth;

class RepositoryObserver
{
    public function created(Repository $repository)
    {
        $evaluation = $repository->evaluation()->create([
            'repository_id' => $repository->id,
        ]);

        // Create empty answers for each question
        Question::get()->each(function ($question) use ($evaluation) {
            Answer::create([
                'evaluation_id' => $evaluation->id,
                'question_id' => $question->id,
                'is_updateable' => 1,
            ]);
        });
        
        
    }

    public function updated(Repository $repository)
    {
        
    }

    public function deleted(Repository $repository)
    {
        //
    }

    public function restored(Repository $repository)
    {
        //
    }

    public function forceDeleted(Repository $repository)
    {
        //
    }
}
