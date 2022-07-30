<?php

namespace App\Jobs\Invitations;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Invitations\SendMailInvitationController as SendMail;

class NewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public $password;
    public $email;
    public $rol;
    public $repository_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($name, $password, $email, $rol, $repository_name)
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->rol = $rol;
        $this->repository_name = $repository_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $sendNotification = new SendMail($this->name, $this->password, $this->email, $this->rol, $this->repository_name); //
        $sendNotification->SendMailInvitation();
    }
}
