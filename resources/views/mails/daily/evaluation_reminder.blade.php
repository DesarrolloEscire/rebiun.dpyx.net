@component('mail::message')
# !Hola {{$name}} !

Te recordamos que aun hay {{__("containerName")}}(s), para ser calificados en lista de espera.

Puedes dar clic en el siguiente boton para iniciar sesion.
@component('mail::button', ['url' => route('login')])
dPyx | Rebiun login
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
