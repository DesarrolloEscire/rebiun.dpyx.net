<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email which is sent when to all admins when a new user requests a new account
 * 
 */
class NewUserRequestToAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $repository;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->repository = $user->repositories()->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.users.requests.administrators')->subject('Nueva solicitud de usuario');
    }
}
