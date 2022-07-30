<div x-data="data()" x-init="mounted()">

    <div class="mb-4">
        @section('header')
            <x-page-title title="Acuerdo entre evaluadores"
                description="Este módulo permite a los evaluadores conciliar la decisión del dictamen."></x-page-title>
        @endsection

        <div class="mb-3 " style="height: 38px; ">
            {{-- rgba(215, 214, 216, 0.363); --}}
            <div class="flex-row border-0 shadow card d-flex justify-content-center"
                style="background-color: rgba(255, 255, 255, 0.521);">
                <div for="" class="mt-2 mb-2 badge badge-info col-4">Intento de conciliación No. 1</div>
            </div>

        </div>

        <div wire:poll class="mt-3 row">

            <div class="col-6">

                <div class="pl-1 mb-2 ml-0 mr-3 border-0 shadow card">
                    <div class="pl-3 card-body">
                        <div class="">
                            <div class="" x-data="{ msg : '' }">
                                <label for="inputFirstname">{{ $evaluator_auth->evaluator_name }}</label>
                                <textarea x-model="msg" @keyup.enter="msg=''" wire:keydown.enter='saveChat()'
                                    wire:model='chat' rows="3" cols="20" class="mb-2 form-control"
                                    placeholder="Comentarios..."></textarea>

                                <div class="flex-row d-flex justify-content-end">
                                    <button @click="msg=''" wire:click='saveChat()' type="button"
                                        class="px-2 mb-2 btn btn-sm btn-info">Enviar comentario</button>
                                </div>

                                <div class="mb-3"
                                    style="overflow-y: scroll; height: 350px; border: 1px solid rgba(200, 200, 200, 0.644);">
                                    <!-- Evaluator 1 Message TODO foreach-->

                                    @if ($messages)
                                        @foreach ($messages as $message)
                                            @if ($message->evaluator_id == $evaluator_auth->id)
                                                <div class="text-left">
                                                    <div class="column">
                                                        <label class="ml-3"
                                                            for="">{{ $evaluator_auth->evaluator_name }}</label><br>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-0 ">
                                                            <div class="mx-2 media-body">
                                                                <div class="w-100">
                                                                    <p class="px-3 py-2 mb-2 rounded bg-light">
                                                                        {{ $message->chat }}
                                                                    </p>
                                                                </div>
                                                                <p class="small text-muted">
                                                                    {{ \Carbon\Carbon::parse($message->updated_at)->format('d-m-Y | h:m:s') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @else

                                                <div class="text-right">
                                                    <div class="column ">
                                                        <label class="mr-3 "
                                                            for="">{{ $evaluatorAssosiated ? $evaluatorAssosiated->evaluator_name : 'N/A' }}</label><br>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-0 ">
                                                            <div class="mx-2 media-body">
                                                                <div class="px-3 py-2 mb-2 rounded"
                                                                    style="background-color: rgb(12, 67, 247);">
                                                                    <p class="mb-0 text-white text-small">
                                                                        {{ $message->chat }}
                                                                    </p>
                                                                </div>
                                                                <p class="small text-muted">
                                                                    {{ \Carbon\Carbon::parse($message->updated_at)->format('d-m-Y | h:m:s') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="mb-3">

                            {{-- STATUS INPUT --}}
                            <label for="" class="text-muted text-uppercase">Status</label><br>
                            <div class="custom-control custom-radio custom-control-inline">

                                <input wire:model='response' type="radio" id="evaluationAcceptedInput" name="status"
                                    class="custom-control-input" value="aprobado" wire:click='sendStatus()'>

                                <label class="custom-control-label" for="evaluationAcceptedInput">
                                    <div class="mb-2 mr-2 badge badge-success">Aceptado</div>
                                </label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input wire:model='response' type="radio" id="evaluationRejected" name="status"
                                    class="custom-control-input" value="rechazado"
                                    {{-- $repository->is_rejected ? 'checked' : '' --}}wire:click='sendStatus()'>
                                <label class="custom-control-label" for="evaluationRejected">
                                    <div class="mb-2 mr-2 badge badge-danger">No aceptado</div>
                                </label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('status.store', [$repository]) }}" x-ref="formSendStatus"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="response" value="{{ $response }}">
                                    {{-- COMMENT INPUT --}}
                                    <label for="" class="text-muted text-uppercase">Comentario</label><br>
                                    <textarea name="comment_body" id="" cols="30" rows="5"
                                        class="form-control mb-3"></textarea>
                                    <button type="button" x-ref="buttonSendStatus" x-on:click="showWarning()"
                                        {{-- data-toggle="modal"  wire:click='stopPoll()' data-target="#SendConciliation" --}} type="button"
                                        class="px-4 btn btn-sm btn-primary text-dark"
                                        {{ $status ? '' : 'disabled' }}>Enviar status</button>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>




            </div>

            <div class="col-6">
                <form action="{{ route('store.checklist', [$repository, $evaluator_auth]) }}" method="POST"
                    class="d-inline">
                    @csrf
                    @method('POST')

                    <div class="mb-2 border-0 shadow card row">
                        <div class=" card-body">

                            {{-- <div class="mb-2 ml-2">{{$evaluatorAssosiated->evaluator_name}}</div> --}}
                            <div class="Text">
                                A continuación, seleccione las categorías en las que haya llegado a un consenso con el
                                otro evaluador. El objetivo es que al llegar a un acuerdo entre evaluadores, todas las
                                categorías estén marcadas por ambos evaluadores. Se sugiere que el diálogo en el chat se
                                enfoque a atender aquellas categorías que no estén seleccionadas por los dos
                                evaluadores.
                            </div>
                            <div class="mt-3 bg-white table-responsive">
                                <table class="table m-0 table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase">Categoría</th>
                                            <th class="text-uppercase">{{ auth()->user()->name }}</th>
                                            <th class="text-uppercase">{{ $evaluatorAssosiated ? $evaluatorAssosiated->evaluator_name : 'N/A' }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $key => $category)
                                            <tr>
                                                <td>
                                                    {{ $category->name }}
                                                </td>
                                                <td>
                                                    {{-- {{dd(auth()->user()->asEvaluator)}} --}}
                                                    {{-- {{dd($category->id)}} --}}
                                                    {{-- {{dd($category->checklists()->whereConciliation($conciliation)->whereEvaluator(auth()->user()->asEvaluator)->first())}} --}}
                                                    {{-- {{ dd( $category->checklists()->whereConciliation($conciliation)->first() ) }} --}}
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="{{ $category->id }}" name="checklist[]"
                                                            {{ !$category->checklists()->whereConciliation($conciliation)->whereEvaluator(auth()->user()->asEvaluator)->exists() ? '' : 'checked' }}>
                                                        <label class="form-check-label" for="">
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($evaluatorAssosiated)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="checklist2[]" disabled
                                                            {{ !$category->checklists()->whereConciliation($conciliation)->whereEvaluator($evaluatorAssosiated)->exists()  ? '' : 'checked' }}>
                                                        <label class="form-check-label"
                                                            for="checklist2{{ $category->id }}">

                                                        </label>
                                                    </div>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3" x-data="{ buttonDisabled: false }">
                                <button x-on:click="buttonDisabled = true" x-bind:disabled="buttonDisabled"
                                    class="btn btn-success btn-wide btn-shadow rounded-0">
                                    <i class="fas fa-paper-plane"></i> Guardar lista
                                </button>
                            </div>
                            <div class="mt-3 mb-3 ml-2">
                                <label for="" class="text-muted ">STATUS actual de
                                    {{ $evaluatorAssosiated ? $evaluatorAssosiated->evaluator_name : 'N/A' }}</label><br>
                                <div
                                    class="mb-2 mr-2 badge {{ $status_contrary ? ($status_contrary == 'aprobado' ? 'badge-success' : 'badge-danger') : 'badge-warning' }}  ">

                                    {{ $status_contrary ? ($status_contrary == 'rechazado' ? 'no aceptado' : $status_contrary) : 'sin enviar' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <div class="pl-3 mb-2 border-0 shadow card column">
                    <div class="card-body ">
                        <h5>Descargar PDF comparativo</h5>

                        <h6> Tabla comparativa de las observaciones emitidas por cada evaluador</h6>
                        <a href="{{ route('comparation.pdf', [$repository]) }}"
                            class="btn btn-danger btn-shadow btn-lg rounded-0">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <style>
            body {
                background-color: #74EBD5;
                background-image: linear-gradient(90deg, #74EBD5 0%, #9FACE6 100%);

                min-height: 100vh;
            }

            ::-webkit-scrollbar {
                width: 5px;
            }

            ::-webkit-scrollbar-track {
                width: 5px;
                background: #f5f5f5;
            }

            ::-webkit-scrollbar-thumb {
                width: 1em;
                background-color: #ddd;
                outline: 1px solid slategrey;
                border-radius: 1rem;
            }

            .text-small {
                font-size: 0.9rem;
            }

            .messages-box,
            .chat-box {
                height: 510px;
                overflow-y: scroll;
            }

            .rounded-lg {
                border-radius: 0.5rem;
            }

            input::placeholder {
                font-size: 0.9rem;
                color: #999;
            }

        </style>
    </div>

    <script>
        function data() {
            return {

                repository: @json($repository),

                showComplementaryQuestions: false,

                mounted() {

                },

                /**
                 *
                 *
                 */

                showWarning() {
                    if (this.$refs.buttonSendStatus.disabled) {
                        return;
                    }

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Estas a punto de enviar un nuevo status de dictamen, para este punto ya deberias de haber llegado a un acuerdo con tu contraparte lo mejor seria calificar igual.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'SI, ENVÍALO',
                        cancelButtonText: 'CANCELAR'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$refs.formSendStatus.submit()
                        }
                    })
                },

            }
        }

    </script>




</div>
