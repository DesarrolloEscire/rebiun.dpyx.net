@component('mail::message')
# {{$user->name}}:

{{$body}}

Gracias,<br>
{{ config('app.name') }}
@endcomponent
