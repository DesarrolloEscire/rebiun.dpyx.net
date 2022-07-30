<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewUserRequestToAdminMail;
use App\Mail\NewUserRequestToAnonymousMail;
use App\Models\User;
use App\Src\Repository\Application\CreateRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class RegisterNewUserRequestController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->password != $request->password_repeated) {
            Alert::error('Contraseña invalida', ' La contraseña repetida no coincide con la contraseña inicial');
            return redirect()->back();
        }

        if (User::where('email', $request->email)->exists()) {
            Alert::error('Error en la solicitud', 'Ya existen una cuenta asociada con el correo electrónico ingresado');
            return redirect()->back();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->assignRole('usuario');

        (new CreateRepository)->handle($user, $request->repository_name);

        $adminUsersMails = User::administrators()
            ->get()
            ->pluck('email')
            ->toArray();

        Mail::to($adminUsersMails)->send(new NewUserRequestToAdminMail($user));
        Mail::to($user->email)->send(new NewUserRequestToAnonymousMail);


        Alert::success(
            'Gracias por tu solicitud.',
            'Hemos enviado un correo de confirmación a la dirección registrada. En cuando el equipo de REBIUN atienda tu solicitud, recibirás un correo de respuesta'
        );

        return redirect()->back();
    }
}
