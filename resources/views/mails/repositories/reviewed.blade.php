@component('mail::message')
# El repositorio {{ $repository->name }} tiene el status:
{{ $repository->status == 'rechazado' ? 'no aceptado' : $repository->status }}.

Hace unas semanas sometiste tu repositorio {{ $repository->name }} para su evaluación de acuerdo con los
criterios CRUE-Rebiun. El resultado de la evaluación no ha sido favorable, sin embargo en el formulario
adjunto puedes ver los comentarios a cada uno de los criterios, con la intención de que puedan ser subsanados
y corregidos. Una vez se hayan hecho los cambios oportunos, puedes someter de nuevo el repositorio
para su re-evaluación.

# Comentarios:

@if ($repository->evaluation->comments()->exists())
    @foreach ($repository->evaluation->comments as $comment)
        {{ $comment->user->name }}: {{ $comment->body }}
    @endforeach
@endif

@if ($repository->conciliation)
    @foreach ($repository->conciliation->comments as $comment)
        {{ $comment->user->name }}: {{ $comment->body }}
    @endforeach
@endif

@component('mail::button',  ['url' => route('repositories.index')])
Ver repositorio
@endcomponent

Saludos cordiales,
{{ config('app.name') }}
@endcomponent
