@component('mail::message')
# Evaluador: {{ $evaluator->evaluator_name }}

Has seleccionado al repositorio "{{ $repository->name }}" para tu evaluación.
Te recordamos que tu evaluación la podrás realizar en cualquier momento, dentro del periodo asignado conforme a la convocatoria.
Tus cambios se irán guardando de forma progresiva, por lo que si no completas tu evaluación, puedes retomarla en otro momento sin perder información.

{{--
@component('mail::button', ['url' => ''])
Button Text
@endcomponent*/--}}

Saludos cordiales,<br>
{{ config('app.name') }}
@endcomponent
