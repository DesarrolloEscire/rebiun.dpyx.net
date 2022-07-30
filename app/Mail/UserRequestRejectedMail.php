<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $body)
    {
        $this->user = $user;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.users.requests.rejected');
    }
}
