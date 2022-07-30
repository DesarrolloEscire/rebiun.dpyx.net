@component('mail::message')
# {{$repository->name}}

Tu repositorio está en la fase de revisión. Te pedimos estar atento/a futuros correos y a tu estatus en la plataforma dPyx.
Saludos cordiales,


{{--
@component('mail::button', ['url' => ''])
Ir a dPyx REBIUN
@endcomponent --}}

Saludos cordiales,<br>
{{ config('app.name') }}
@endcomponent
