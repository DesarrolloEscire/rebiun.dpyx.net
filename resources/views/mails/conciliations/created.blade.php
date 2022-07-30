@component('mail::message')

# Estimado y apreciada colega:

Se ha iniciado un proceso de conciliación para el repositorio 
{{ $conciliation->repository->name }}. Los evaluadores  involucrados 
establecerán un diálogo para acordar el resultado de la 
evaluación y se te notificará a la brevedad posible. 

Saludos cordiales,<br>
{{ config('app.name') }}
@endcomponent
