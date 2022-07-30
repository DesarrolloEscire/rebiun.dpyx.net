@component('mail::message')
# Hola {{$name}}: <br /> {{$rol == 'usuario' ? $msgUser['msg1']: $msgEvaluator['msg1']}}
# {{$rol== 'usuario' ? $msgUser['msg2']: $msgEvaluator['msg2']}}

Nombre de usuario: {{$email}} <br/>
Contraseña : {{$password}}

@component('mail::button', ['url' => route('login')])
Aceptar Invitación
@endcomponent

# {{$rol == 'usuario' ? $msgUser['msg3']: $msgEvaluator['msg3']}}

@if($rol == 'usuario')
@component('mail::button', ['url' => 'https://dpyx.site/usuario/#jp-carousel-307'])
Manual de usuario
@endcomponent
@elseif($rol == 'evaluator'))
@component('mail::button', ['url' => 'https://dpyx.site/evaluador/#jp-carousel-339'])
Manual de usuario
@endcomponent
@endif

Saludos Cordiales,<br>
{{ config('app.name') }}
@endcomponent
