<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\StartEvaluationMail;
use App\Models\Repository;

class SendMailNotificationStartEvaluation extends Controller
{
    //
    public $repository;

    public function __construct($sender)
    {
        $this->repository = $sender;
    }


    public function SendMailNotification()
    {
        Mail::to(
            $this->repository->responsible->email
        )->send(
            new StartEvaluationMail($this->repository)
        );
    }
}
