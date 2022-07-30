<?php

namespace App\Http\Livewire\Answers;

use App\Models\Answer;
use App\Models\Evaluator;
use App\Models\Conciliation;
use App\Models\Repository;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

//use App\Models\Observation;

class Show extends Component
{

    public $answer;
    public $evaluator;
    public $observation;
    public $user;
    public $repo_unconciliated;
    //Answer $answer,
    public function mount(Answer $answer, Evaluator $evaluator,Repository $repository){

        $this->answer = $answer;
        $this->evaluator= $evaluator;

        $this->user = Auth::user();

            $authEvaluator = Evaluator::where('evaluator_id','=',$this->user->id)->first();


        if($this->user->is_evaluator){

        $this->repo_unconciliated = Conciliation::where('evaluator_solve_id','=', $authEvaluator->id)
                                                  ->where('repository_id','=', $repository->id)->first();
       // dd($this->repo_unconciliated,  $authEvaluator->id);

            }


    }

    public function render()
    {
        foreach($this->answer->observations as $observation){
            if($observation->evaluator_id == $this->evaluator->id){
                $this->observation = $observation;
                break;
            }
        }


        return view('livewire.answers.show');
    }
}
