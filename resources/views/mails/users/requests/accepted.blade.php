@component('mail::message')
# {{$user->name}}:

Tú solicitud ha sido aceptada por nuestros administradores.
Ahora puedes iniciar sesión dando click en el siguiente enlace

@component('mail::button', ['url' => route('login')])
Iniciar sesión
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
