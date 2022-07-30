<?php

namespace App\Src\User\Application\Create;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use RealRashid\SweetAlert\Facades\Alert;

class CreateAdminUser
{

    private $request;

    public function __construct(FormRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $user = new User;
        $user->name = $this->request->name;
        $user->phone = $this->request->phone;
        $user->email = $this->request->email;
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt($this->request->password);
        $user->save();

        $user->assignRole($this->request->role);

        Alert::success('¡Usuario agregado como administrador!', 'El usuario ha sido añadido a la base de datos.');
    }
}
