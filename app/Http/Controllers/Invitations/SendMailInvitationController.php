<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationNewUserMail;


class SendMailInvitationController extends Controller
{

   // public $user;

    public $name;
    public $password;
    public $email;
    public $rol;
    public $repository_name;

    protected $msgEvaluator;

    protected $msgUser;

  /*  $user = new User;
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->name = $request->name;*/

    public function __construct($name,$password,$email,$rol,$repository_name)
    {

        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->rol = $rol;
        $this->repository_name = $repository_name;
        //Estos textos hay que pasarlos al archivo de lang y traerlos desde alla
        $msgtmpU='Bienvenido(a) a la plataforma dPyx para la evaluación de tu repositorio ('. $repository_name .'), la cual será gestionada para '. config('app.name') ;

        $this->msgUser = array('msg1' => $msgtmpU,
                               'msg2' =>  'Para completar tu registro, por favor da click en "Aceptar invitación" y confirma la información de tu perfil.',
                                'msg3'=> 'Una vez completes los campos solicitados, podrás empezar a responder el cuestionario. Las instrucciones las podrás encontrar en el Manual de usuario siguiente.');

        $msgtmpE='Bienvenido(a) a la plataforma dPyx, para le evaluación de los repositorios de la red CRUEN REBIUN. Agradecemos tu apoyo como evaluador de este ejercicio, aportando tu conocimiento y experiencia en el tema.';

        $this->msgEvaluator = array(
                                'msg1'=> $msgtmpE,
                                'msg2'=> 'Para completar tu registro, por favor da click en "Aceptar invitación" y confirma la información de tu perfil.',
                                'msg3'=> 'Una vez completes los campos solicitados, podrás seleccionar al(los) repositorio(s) que te gustaría evaluar. Las instrucciones las podrás encontrar en este Manual de usuario.'
        );

       // $this->user = $user;
    }


    public function SendMailInvitation(){
               Mail::to($this->email)->send(new InvitationNewUserMail($this->name,$this->password,$this->email,$this->rol,$this->repository_name,$this->msgEvaluator, $this->msgUser));

    }

}
