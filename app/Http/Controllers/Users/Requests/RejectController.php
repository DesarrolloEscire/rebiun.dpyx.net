<?php

namespace App\Http\Controllers\Users\Requests;

use App\Http\Controllers\Controller;
use App\Mail\UserRequestRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class RejectController extends Controller
{
    public function __invoke(User $user, Request $request)
    {
        $user->repositories()->delete();
        $user->delete();

        Mail::to($user->email)->send(new UserRequestRejectedMail($user, $request->email_body));

        Alert::success('Usuario rechazado', 'El usuario ha sido rechazado, por lo tanto, no podrá acceder al sistema');
        return redirect()->back();
    }
}
