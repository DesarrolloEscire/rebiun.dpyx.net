<?php

namespace App\Observers;

use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class EvaluationObserver
{
    public function created(Evaluation $evaluation)
    {
        //
    }

    public function updated(Evaluation $evaluation)
    {
        
    }

    public function deleted(Evaluation $evaluation)
    {
        //
    }

    public function restored(Evaluation $evaluation)
    {
        //
    }

    public function forceDeleted(Evaluation $evaluation)
    {
        //
    }
}
