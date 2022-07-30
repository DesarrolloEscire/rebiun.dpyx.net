<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SelectedEvaluationMail;

class SendMailSelectedRepository extends Controller
{
    public $evaluator;
    public $repository;

    public function __construct($evaluator, $repository)
    {
        $this->evaluator = $evaluator;
        $this->repository = $repository;
    }

   public function SendMailNotification(){

    Mail::to($this->evaluator->responsible->email)->send(new SelectedEvaluationMail($this->evaluator, $this->repository));

   }
}
