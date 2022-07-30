@component('mail::message')
# Repositorio: {{ $conciliation->repository->name }}

Los evaluadores no han podido llegar a un acuerdo en la etapa de conciliación.
por lo que se comenzará nuevamente dicha etapa.

Saludos cordiales,<br>
{{ config('app.name') }}
@endcomponent
