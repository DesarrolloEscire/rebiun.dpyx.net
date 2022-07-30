<div class="mb-4" x-data="data()" x-init="mounted()">

    @section('header')
        <x-page-title title="Cuestionario"
            description="Este módulo permite responder a las preguntas para evaluar un repositorio.">
        </x-page-title>
    @endsection

    <div class="row">
        <div class="mb-3 col-12 d-flex justify-content-end">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck1"
                    wire:click="toggleSupplementaryQuestions()">
                <label class="custom-control-label text-uppercase text-danger" for="customCheck1">
                    ¿Mostrar preguntas complementarias?
                </label>
            </div>
        </div>
    </div>

    <ul class="mb-3 nav nav-justified">
        @foreach ($categories as $category)
            <li {{-- class="nav-item border-bottom mr-1 {{$categoryChoosed->id == $category->id ? 'border-danger bg-red-light' : ''}}"> --}}
                class="nav-item border-bottom mr-1 {{ $categoryChoosed->id == $category->id ? 'border-danger' : '' }}">
                <a href="{{ route('evaluations.categories.questions.index', [$evaluation, $category]) }}"
                    class="nav-link active">
                    @if ($category->is_answered)
                        {{-- <i class="fas fa-trash"></i> --}}
                        <b>
                            <i class="nav-link-icon fas fa-check-circle text-success"></i>
                        </b>
                    @else
                        <i class="nav-link-icon fas fa-circle text-warning"></i>
                        {{-- <i class="nav-link-icon pe-7s-settings"></i> --}}
                    @endif
                    <span>{{ $category->short_name ?? $category->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    @foreach ($subcategories as $subcategory)
        @if ($subcategory->has_questions)
            <div class="mb-3 row">
                <div class="col-12">
                    <h4 class="text-uppercase text-muted">
                        <span>{{ $subcategory->name }}</span>
                    </h4>
                </div>

                @if (($showComplementaryQuestions && $subcategory->questions->where('is_optional', 1)->count()) || $subcategory->questions->where('is_optional', 0)->count())
                    <div class="col-12 mb-3">
                        <div class="bg-white shadow table-responsive">

                            <table class="table m-0 table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase">Pregunta</th>
                                        <th class="text-uppercase">Respuestas</th>
                                        <th class="text-uppercase">Status</th>
                                        <th class="text-uppercase">Observaciones</th>
                                        <th class="text-uppercase">Historial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subcategory->questions as $question)
                                        @if (($showComplementaryQuestions && $question->is_optional == 1) || $question->is_optional == 0)
                                            <tr>
                                                <td>
                                                    {{ $question->description }}
                                                    @if ($question->help_text)
                                                        <span tabindex="0" data-toggle="popover" data-trigger="focus"
                                                            title="Ayuda" data-content="{{ $question->help_text }}">
                                                            <i class="float-right fas fa-question-circle text-info"></i>
                                                        </span>
                                                    @endif
                                                    @if ($question->answer->choice && $question->answer->choice->punctuation > 0 && $question->has_description_field)
                                                        <br><br>
                                                        <span
                                                            class="text-info">{{ $question->description_label }}</span>
                                                        <textarea rows="2" class="form-control border-info" required
                                                            wire:change="updateDescription({{ $question->answer }}, $event.target.value)"
                                                            {{ $evaluation->is_answerable ? '' : 'disabled readonly' }}>{{ $question->answer->description }}</textarea>
                                                    @endif
                                                </td>
                                                <td>

                                                    <select class="form-control" x-ref="{{ $question->id }}"
                                                        wire:loading.attr="disabled" wire:target="storeAnswer"
                                                        {{ $evaluation->is_answerable ? '' : 'readonly disabled' }}
                                                        x-on:change="$wire.storeAnswer({{ $question->id }}, $refs[{{ $question->id }}].options[$refs[{{ $question->id }}].selectedIndex].value )">
                                                        <option value=""
                                                            {{ $question->answer->choice ? '' : 'selected' }} hidden>
                                                            seleccionar
                                                        </option>
                                                        @foreach ($question->choices as $choice)
                                                            <option value="{{ $choice->id }}"
                                                                {{ $question->answer->choice && $question->answer->choice->id == $choice->id ? 'selected' : '' }}>
                                                                {{ $choice->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <div wire:loading wire:target="storeAnswer">
                                                        <div class="spinner-border text-secondary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>
                                                    <div wire:loading.remove wire:target="storeAnswer">
                                                        <span
                                                            class="badge badge-pill {{ $question->answer->choice ? 'badge-success' : 'badge-warning' }}">{{ $question->answer->choice ? 'contestada' : 'pendiente' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($evaluator != null && !$evaluator_unconciliated)
                                                        @if ($evaluation->is_reviewable)
                                                            @if ($question->answer)
                                                                @php
                                                                    $existing_observation = false;
                                                                    foreach ($question->answer->observations as $observation) {
                                                                        if ($evaluator->id == $observation->evaluator_id) {
                                                                            if ($observation) {
                                                                                $existing_observation = true;
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                                <a href="{{ route('answers.show', [$question->answer->id, $evaluator->id, $evaluation->repository->id]) }}"
                                                                    class="btn {{ $existing_observation ? 'btn-danger' : 'btn-info' }} btn-shadow rounded-0">
                                                                    <i class="fas fa-plus"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endif

                                                    @if (!$evaluator || $evaluator_unconciliated)
                                                        @if ($question->answer->observations->count())
                                                            @foreach ($question->answer->observations as $observation)
                                                                @if ($loop->first)
                                                                    {{-- <a href="{{ route('answers.show', [$question->answer->id, $observation->evaluator_id, $evaluation->repository->id]) }}"
                                                                        class="btn btn-secondary btn-shadow rounded-0">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a> --}}
                                                                @else
                                                                    <a href="{{ route('answers.show', [$question->answer->id, $observation->evaluator_id, $evaluation->repository->id]) }}"
                                                                        class="btn btn-success btn-shadow rounded-0">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif


                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-shadow rounded-0"
                                                        data-toggle="modal"
                                                        data-target="#showAnswerHistory{{ $question->answer->id }}">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                    <x-modals.answers.history :answer="$question->answer" />
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-lg-3">
                                <div class="mb-3 text-left card-shadow-primary widget-chart widget-chart2 card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <h7 class="widget-subheading">
                                                Puntuación de los requisitos
                                            </h7>
                                            <div class="widget-chart-flex">
                                                <div class="mb-0 widget-numbers w-100">
                                                    <div class="widget-chart-flex">
                                                        <div class="fsize-4 text-danger">
                                                            <span>
                                                                {{ $subcategory->total_required_punctuation }}%
                                                            </span>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <div
                                                                class="ml-auto widget-title font-size-lg font-weight-normal text-muted">
                                                                <span class="pl-2 text-danger">
                                                                    <span class="pr-1">
                                                                        de
                                                                    </span>
                                                                    <span>
                                                                        {{ $subcategory->max_required_punctuation }}%
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>
                @else

                    <div class="col-12">
                        <div class="mb-3 border-0 shadow card">
                            <div class="card-body">
                                <div class="alert alert-info w-100">
                                    No hay ningúna pregunta para mostrar.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endforeach



    <div class="mb-3 d-flex justify-content-end">

        <a href="{{ route('evaluations.categories.questions.index', [$evaluation, $nextCategory]) }}"
            class="btn btn-success btn-shadow btn-wide rounded-0">

            <i class="fas fa-save"></i> {{ $lastCategory ? 'Primera Categoría' : 'Siguiente Categoría' }}
        </a>

        @if (config('app.is_evaluable'))
            @if (Auth::user()->hasRole('usuario') && ($evaluation->is_answered || !config('app.answers_are_necessary')))
                <div class="ml-3 row">
                    <div class="col-12">
                        <form action="{{ route('evaluations.send', [$evaluation]) }}" method="POST"
                            x-ref="formSendEvaluation">
                            @csrf
                        </form>

                        <button class="shadow btn btn-info btn-wide rounded-0" {{ $announcement ? '' : 'disabled' }}
                            x-ref="buttonSendQuestionary" x-on:click="showWarning()">
                            <i class="fas fa-paper-plane"></i> {{ __('Enviar cuestionario') }}
                        </button>
                    </div>
                </div>
            @endif
        @endif

    </div>



    @can('create observations')
        @if ($evaluation->is_reviewable)
            <div class="row">
                <div class="col-12">

                    <form
                        action="{{ $evaluator_unconciliated ? route('unconciliated.send', [$evaluation->repository]) : route('repositories.send', [$evaluation->repository]) }}"
                        method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3 border-0 shadow card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3 col-12">
                                        @if ($evaluator_unconciliated)
                                            <div class="mb-3 text-center col-12 bg-warning">
                                                <span>Esta resolución será definitiva ya ha pasado por un
                                                    procedimiento</span>
                                            </div>
                                        @endif
                                        <label for="" class="text-muted text-uppercase">Status</label><br>



                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="evaluationAcceptedInput" name="status"
                                                class="custom-control-input" value="aprobado"
                                                {{ $repository->is_aproved ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="evaluationAcceptedInput">
                                                <div class="mb-2 mr-2 badge badge-success">Aceptado</div>
                                            </label>
                                        </div>


                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="evaluationRejected" name="status"
                                                class="custom-control-input" value="rechazado"
                                                {{ $repository->is_rejected ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="evaluationRejected">
                                                <div class="mb-2 mr-2 badge badge-danger">No aceptado</div>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <label for="" class="text-muted text-uppercase">Comentarios</label>
                                        <textarea name="comments" id="" cols="30" rows="5"
                                            class="form-control">Su repositorio ha sido enviado.</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">

                            <div x-data="{ buttonDisabled: {{ $user->is_evaluator ? 'false' : 'true' }} }">
                                <button x-on:click="buttonDisabled = true" x-bind:disabled="buttonDisabled"
                                    class="btn btn-success btn-wide btn-shadow rounded-0">
                                    <i class="fas fa-paper-plane"></i> Enviar cuestionario
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endcan

    @if ($evaluation->comments()->exists())
        <div class="row">
            <div class="col-12">
                <div class="mb-3 border-0 shadow card">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($evaluation->comments()->orderBy('created_at','desc')->get() as $comment)
                                <div class="col-12 mb-2">
                                    <span>
                                        <b class="text-info">
                                            <i class="fas fa-user"></i>
                                            {{ $comment->user->name }}
                                            <small>(📅 {{ $comment->created_at }})</small>
                                        </b>
                                        <hr class="m-0">
                                        <span>{{ $comment->body }}</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function data() {
            return {
                showComplementaryQuestions: false,

                mounted() {

                },

                /**
                 *
                 *
                 */

                showWarning() {
                    if (this.$refs.buttonSendQuestionary.disabled) {
                        return;
                    }



                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "No podrás modificar ninguna respuestas hasta que el evaluador te envíe los resultados.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'SI, ENVÍALO',
                        cancelButtonText: 'CANCELAR'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$refs.formSendEvaluation.submit()
                        }
                    })
                },

            }
        }

    </script>

</div>
