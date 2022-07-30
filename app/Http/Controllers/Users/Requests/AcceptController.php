<?php

namespace App\Http\Controllers\Users\Requests;

use App\Http\Controllers\Controller;
use App\Mail\UserRequestAcceptedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class AcceptController extends Controller
{
    public function __invoke(User $user)
    {
        $user->verify();

        Mail::to($user->email)->send(new UserRequestAcceptedMail($user));

        Alert::success('Usuario verificado', 'El usuario ha sido verificado exitosamente');
        return redirect()->back();
    }
}
