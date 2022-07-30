<?php

namespace App\Mail;

use App\Models\Evaluation;
use App\PDFs\EvaluationPDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $evaluation;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $this->pdf = (new EvaluationPDF($evaluation))->build();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.repositories.evaluation')
            ->subject('Evaluación enviada')
            ->attachData($this->pdf->output(), 'evaluacion.pdf');
    }
}
