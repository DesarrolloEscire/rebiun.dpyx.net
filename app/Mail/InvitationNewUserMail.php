<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class InvitationNewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

//   public $user;
        public $name;
        public $password;
        public $email;
        public $rol;
        public $repository_name;
        public $msgEvaluator;
        public $msgUser;

     public function __construct($name,$password,$email,$rol,$repository_name,$msgEvaluator,$msgUser)
    {
        //$this->user = $user;
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->rol = $rol;
        $this->repository_name = $repository_name;
        $this->msgEvaluator = $msgEvaluator;
        $this->msgUser = $msgUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('mails.invitations.invitation_new_user')
            ->subject('Invitación para repositorio');
            return $message;
    }
}
