<?php

namespace App\Mail;

use App\Models\Conciliation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConciliationDisagreementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $conciliation;

    public function __construct(Conciliation $conciliation)
    {
        $this->conciliation = $conciliation;
    }

    public function build()
    {
        return $this
            ->markdown('mails.conciliations.disagreement')
            ->subject('Desacuerdo de conciliación');
    }
}
