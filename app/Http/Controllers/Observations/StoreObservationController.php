<?php

namespace App\Http\Controllers\Observations;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreObservationRequest;
use App\Models\Observation;
use App\Services\ObservationService;
//use Illuminate\Support\Arr;
//use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use App\Models\Evaluator;
use App\Models\Answer;

class StoreObservationController extends Controller
{
    public function __invoke(StoreObservationRequest $request)
    {
        $evaluators = Evaluator::where('evaluator_id', '=', Auth::user()->id)->first();


        $evaluator_id=$evaluators->id;//actual evaluator

        $observation_check = Observation::where('evaluator_id','=', $evaluator_id)
                                        ->where('answer_id', '=' , $request->answer_id)
                                        ->first();

    $new_observation=false;

        if ($observation_check != null)
        {
            $observation = Observation::updateOrCreate([
                'answer_id' => $request->answer_id, 'evaluator_id'=> $evaluator_id
            ], [
                'answer_id' => $request->answer_id,
                'description' => $request->description ?? '',
                'evaluator_id' => $evaluator_id

            ]);
        }else{
        $observation = Observation::Create([
            'answer_id' => $request->answer_id,
            'description' => $request->description ?? '',
            'evaluator_id' => $evaluator_id
        ]);
        $new_observation=true;
    }

    $this_answer = Answer::find($request->answer_id);

        if($this_answer->observations->count()){
           // dd($this_answer->observations);
        foreach($this_answer->observations as $observation_for){
            if($evaluator_id == $observation_for->evaluator_id){

             //   $this_answer->observations()->sync($observation->id);

                }else{

                    if($new_observation){
                        $this_answer->observations()->attach($observation->id);
                    }
                }
            }
        }else{

            $this_answer->observations()->attach($observation->id);

        }
        //
        (new ObservationService)($observation)->removeFiles($request->filesToDelete);

        //

        if ($request->hasFile('files')) {
        //    dump($request->hasFile('files'));

            // TODO store files with their original and time name instied of only time
            (new ObservationService)($observation)->storeFiles($request->file('files'));
        }

        $observation->save();

        Alert::success('¡Observación creada!');
        return redirect()->route('evaluations.categories.questions.index', [$observation->answer->evaluation, $observation->answer->question->category]);
    }
}
