<?php

namespace App\Mail;

use App\Models\Conciliation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewConciliationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $conciliation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Conciliation $conciliation)
    {
        $this->conciliation = $conciliation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.conciliations.created')
            ->subject('Conciliació iniciada');
    }
}
