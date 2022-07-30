@component('mail::message')

El usuario {{ auth()->user()->name }} ha enviado su cuestionario para la evaluación del repositorio: "{{ $evaluation->repository->name }}"
Una vez que sean asignados los evaluadores para su revisión, será informado/a del inicio de la evaluación.

Saludos cordiales,<br>
{{ config('app.name') }}
@endcomponent
