<?php

namespace App\Src\User\Application\Create;

use App\Jobs\Invitations\NewUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use RealRashid\SweetAlert\Facades\Alert;

class CreateEvaluatorUser
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

        $name = $this->request->name;
        $password = $this->request->password;
        $email = $this->request->email;
        $rol = $user->roles()->first()->name;

        $user->evaluators()->create([
            'evaluator_name' => $this->request->name,
            'evaluator_id' => $user->id
        ]);

        NewUser::dispatch($name, $password, $email, $rol, $this->request->repository_name); //Send mail by job //run in background
        Alert::success('¡Usuario agregado como evaluador!', 'El usuario ha sido añadido a la base de datos y se le envío una notificación via email');
    }
}
