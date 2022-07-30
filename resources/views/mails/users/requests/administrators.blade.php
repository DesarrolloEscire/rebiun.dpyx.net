@component('mail::message')
El repositorio "{{$repository->name}}" ha solicitado una cuenta para realizar su evaluación

@component('mail::button', ['url' => route('users.index')])
Ver solicitud
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
