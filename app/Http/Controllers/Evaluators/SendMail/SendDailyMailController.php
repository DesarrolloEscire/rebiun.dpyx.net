<?php

namespace App\Http\Controllers\Evaluators\SendMail;

use App\Http\Controllers\Controller;
use App\Models\Evaluator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyEvaluatorsMail;
use App\Models\Announcement;
use App\Models\Evaluation;
use App\Models\Repository;
use Illuminate\Support\Facades\Storage;

class SendDailyMailController extends Controller
{
    public function SendMail(){

     $announcement = Announcement::active()->first();

   //  $texto = 'Andres ' . 'andrestor@gmail.com';
    // Storage::append("testschedule.txt", 'mailable '. $texto);

     if($announcement){


        $repositories = Repository::all();
        $evaluators = Evaluator::all();
     //   $mailable_evaluators = [];
        $send = false;
      foreach ($evaluators as $evaluator){
          $send = false;
        foreach ($repositories as $repository) {
            if (($repository->evaluators->count() < 2) && ($repository->evaluation->status == "en revisión")) {
                $send = true;
                foreach($repository->evaluators as $eval){
                    if($eval->id == $evaluator->id){
                        $send = false;
                    }
                }
            }
        }

foreach ($repositories as $repository) {
        $unconciliateds= \App\Models\StatusResolution::where('status_conciliation', '=', 'unconciliated')
                                                             ->where('evaluation_id', '=',$repository->evaluation->id)
                                                              ->get();
                $ids=[];
                foreach ($unconciliateds as $evals) {
                   $ids[]=$evals->evaluator_id;

                }



                $repeated=null;

    foreach ($unconciliateds as $key => $unconciliated){

                      $evaluation = \App\Models\Evaluation::firstWhere('id','=', $unconciliated->evaluation_id);
                      $conciliation = \App\Models\Conciliation::firstWhere('repository_id', '=', $evaluation->repository->id);

            if ($evaluator->id != $ids[0] && $evaluator->id != $ids[1] ) {
                 $testo = $evaluator->id . " " . $ids[0] ." " . $ids[1];
             Storage::append("testschedule.txt", 'mailable '. $testo);
                if ($unconciliated->status_conciliation == 'unconciliated'){
                    if(!$conciliation->evaluator_solve_id){
                        if(!($repeated == $unconciliated->evaluation_id)){
                            $send = true;

                        }
                    }
                }
            }

        $repeated = $unconciliated->evaluation_id;
    }


}

if($send){


             $testo = $evaluator->responsible->name . $evaluator->responsible->email;
             Storage::append("testschedule.txt", 'mailable '. $testo);

           //  Mail::to($evaluator->responsible->email)->send(new DailyEvaluatorsMail($evaluator->responsible->name));


    }

    }
}//end if announcement

/*
        $mailable_evaluators = array_unique($mailable_evaluators);

        $testo = "si se graba: " . date("Y-m-d H:i:s");
        Storage::append("testschedule.txt", 'mailable'.implode(",", $mailable_evaluators));*/

       // $testo = $mailable_evaluators;
       // Storage::append("testschedule.txt", $testo);


        //Mail::to('andres@gmail.com')->send(new DailyEvaluatorsMail);






     }

}
