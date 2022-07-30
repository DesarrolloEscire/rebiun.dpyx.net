<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SelectedEvaluationMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $evaluator;
    public $repository;

    public function __construct($evaluator, $repository)
    {
     $this->evaluator = $evaluator;
     $this->repository = $repository;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('mails.notifications.evaluatorselectmail')
        ->subject('Nuevo repositorio');
        return $message;
    }
}
