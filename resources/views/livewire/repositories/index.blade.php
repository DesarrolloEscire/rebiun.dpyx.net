<div x-data="data()" {{-- x-init="mounted()" --}}>
    <div class="mb-4">

        @section('header')
            <x-page-title title="Lista de {{ __('containerNamePlural') }}"
                description="Este módulo permite listar los repositorios evaluados o pendinentes de  evaluación."></x-page-title>
        @endsection

        <div class="mb-3 row">

            {{--  --}}
            <div class="col-12 col-md-3 mb-2">
                <x-input-search />
            </div>

            <div class="col-12 col-md-3 mb-2">
                <select wire:model='search_filter' class="form-select border-0 shadow-sm"
                    aria-label=".form-select-sm example">
                    <option selected>Sin filtro</option>
                    <option>Filtrar sin progreso</option>
                    <option>Filtrar en evaluación</option>
                    <option>Filtrar aprobado</option>
                    <option>Filtrar rechazado</option>
                </select>
                {{-- </div> --}}
            </div>


            <div class="text-right col-12 col-md-6 text-right">
                @if (auth()->user()->is_evaluator || auth()->user()->is_admin)
                    <a class="mb-2 btn btn-success btn-shadow rounded-0" data-toggle="tooltip"
                        title="seleccionar repositorios" href="{{ route('repositories.chooserepositories.index') }}">
                        <i class="fas fa-clipboard-check"></i>
                    </a>
                    <a href="{{ route('repositories.statistics.all') }}" data-toggle="tooltip" title="aglomerado"
                        class="mb-2 btn btn-info btn-shadow rounded-0">
                        <i class="fas fa-chart-pie"></i>
                    </a>
                @endif
            </div>

        </div>

        <div class="mb-3 bg-white shadow table-responsive">
            <table class="table m-0 table-bordered">
                <thead>
                    <tr>
                        <th class="text-uppercase">Nombre</th>
                        <th class="text-uppercase">{{ __('containerName') }}</th>
                        <th class="text-uppercase">Evaluación</th>
                        <th class="text-uppercase">Encargado</th>
                        @if (config('app.is_evaluable') && (auth()->user()->is_evaluator || auth()->user()->is_admin || config('dpyx.evaluators_shownables')))
                            <th class="text-uppercase">Evaluadores</th>
                        @endif
                        @can('edit evaluations')
                            <th class="text-uppercase">Conciliación</th>
                        @endcan
                        <th class="text-uppercase">Gráfica de resultados</th>
                        {{-- @if (config('app.is_evaluable') && (auth()->user()->is_evaluator || auth()->user()->is_admin || config('dpyx.evaluators_shownables'))) --}}
                        <th class="text-uppercase">Cuestionario</th>
                        {{-- @endif --}}
                        <th class="text-uppercase">Historial</th>
                        <th class="text-uppercase">PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repositories as $repository)
                        <tr>
                            <td>{{ $repository->name }}</td>
                            <td>
                                <span class="badge badge-{{ $repository->status_color }}">
                                    @if ($repository->evaluation->in_review)
                                        En evaluación
                                    @else

                                        {{ $repository->status == 'rechazado' ? 'no aceptado' : $repository->status }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $repository->evaluation->status_color }}">
                                    @if ($repository->is_aproved || $repository->is_rejected)
                                        Concluido
                                    @else
                                        {{ $repository->evaluation->status }}
                                    @endif
                                </span>
                            </td>
                            <td nowrap>
                                {{ $repository->responsible->name }}
                            </td>

                            @if (config('app.is_evaluable') && (auth()->user()->is_evaluator || auth()->user()->is_admin || config('dpyx.evaluators_shownables')))
                                <td>

                                    @foreach ($repository->evaluators as $evaluators)
                                        <h6> <span
                                                class="badge badge-secondary">{{ $evaluators->evaluator_name }}</span>
                                        </h6>
                                        {{-- <input type="text" class="mt-1 badge badge-pill" readonly value="{{ $evaluators->evaluator_name }}"> --}}
                                    @endforeach

                                    @php
                                        $eval = \App\Models\Evaluator::where('evaluator_id', '=', Auth::user()->id)->first();
                                        //   dump(($eval));
                                        $status_res = null;
                                        if ($eval) {
                                            $status_res = \App\Models\StatusResolution::where('evaluation_id', '=', $repository->evaluation->id)
                                                ->where('evaluator_id', '=', $eval->id)
                                                ->first();
                                        }
                                        
                                        //  isset($repository->conciliation->evaluator_solve_id)
                                        $evaluator_name = null;
                                        if (isset($repository->conciliation->evaluator_solve_id)) {
                                            // dump($repository->conciliation->evaluator_solve_id);
                                            $evaluator_name = \App\Models\Evaluator::where('id', '=', $repository->conciliation->evaluator_solve_id)->first();
                                            //  dump( $evaluator_name);
                                        }
                                    @endphp


                                    @if (!$status_res)

                                        @if (isset($repository->conciliation->evaluator_solve_id) && $repository->conciliation->evaluator_solve_id)

                                            <h6> <span
                                                    class="badge badge-warning">{{ $evaluator_name ? $evaluator_name->evaluator_name : '' }}</span>
                                            </h6>


                                        @endif

                                    @endif

                                </td>
                            @endif
                            @can('edit evaluations')

                                <td>
                                    @php
                                        $eval = \App\Models\Evaluator::where('evaluator_id', '=', Auth::user()->id)->first();
                                        //   dump(($eval));
                                        if ($eval) {
                                            $status_res = \App\Models\StatusResolution::where('evaluation_id', '=', $repository->evaluation->id)
                                                ->where('evaluator_id', '=', $eval->id)
                                                ->first();
                                        }
                                        
                                        if (!Auth::user()->is_admin) {
                                            $res = $repository->conciliation && isset($status_res) ? $status_res->status_conciliation == 'open' : false;
                                            //    dd($res);
                                            $unc = false;
                                        
                                            if ($status_res != null) {
                                                if ($status_res->status_conciliation == 'unconciliated') {
                                                    $res = false;
                                                    $unc = true;
                                                }
                                            } elseif (isset($repository->conciliation->evaluator_solve_id)) {
                                                $res = false;
                                                $unc = true;
                                            }
                                        }
                                        
                                    @endphp
                                    @if (!Auth::user()->is_admin)
                                        <a
                                            href=" {{ ($repository->conciliation && isset($status_res) && $status_res != null ? $status_res->status_conciliation == 'open' : false) ? route('conciliation.index', [$repository]) : '' }} ">


                                            <button
                                                class="btn {{ $res && !$unc ? 'btn-info' : '' }} {{ !$res && $unc ? 'btn-warning' : 'btn-secondary' }} {{-- dump($unc) --}} btn-shadow rounded-0"
                                                {{ $res && !$unc ? ($res ?: '') : 'disabled' }}>

                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </a>
                                    @endif
                                </td>
                            @endcan
                            <td>
                                <a href="{{ route('repositories.statistics.show', [$repository]) }}"
                                    class="btn btn-info btn-shadow rounded-0 {{ $repository->evaluation->answers->whereNotNull('choice_id')->count() ? '' : 'disabled' }}">
                                    <i class="fas fa-chart-pie"></i>
                                </a>
                            </td>
                            {{-- @if (config('app.is_evaluable') && (auth()->user()->is_evaluator || auth()->user()->is_admin || config('dpyx.evaluators_shownables'))) --}}
                            <td>

                                <a href="{{ route('evaluations.categories.questions.index', [$repository->evaluation, $firstCategory]) }}"
                                    class="btn btn-{{ $repository->evaluation->is_reviewed && $repository->is_aproved ? 'secondary' : 'primary' }} btn-shadow rounded-0 {{ $repository->evaluation->is_viewable ? '' : 'disabled' }}">
                                    <i class="fas fa-scroll"></i>
                                </a>

                            </td>
                            {{-- @endif --}}
                            <td>
                                {{-- <button type="button" class="btn btn-info btn-shadow rounded-0" data-toggle="modal"
                                    data-target="#showEvaluationHistory{{ $repository->evaluation->id }}">
                                    <i class="fas fa-history"></i>
                                </button>
                                <x-modals.evaluations.history :evaluation="$repository->evaluation" /> --}}
                                <button type="button" class="btn btn-info btn-shadow rounded-0" data-toggle="modal"
                                    data-target="#showRepositoryHistory{{ $repository->id }}">
                                    <i class="fas fa-history"></i>
                                </button>
                                <x-modals.repositories.history :repository="$repository" />
                            </td>
                            <td>

                                @if ($repository->evaluation->is_pdf_downloable)

                                    <canvas width="300" height="150" hidden
                                        id="repositoryQualification{{ $repository->id }}"></canvas>

                                    <form method="POST" x-ref="formPDF{{ $repository->id }}"
                                        action="{{ route('evaluations.pdf', [$repository->evaluation]) }}">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" id="pic{{ $repository->id }}" name="picture" value
                                            class="form-control">

                                        <div>
                                            <button x-on:click="makeGraph({{ $repository->id }})"
                                                class="btn btn-danger btn-shadow rounded-0">

                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        </div>


                                    </form>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="">
            {{ $repositories->links() }}
        </div>

    </div>


    <script>
        class QualificationValueObject {
            constructor(qualification) {
                this.qualification = qualification
            }

            color() {
                if (this.qualification <= 0) {
                    return '#ff6384'
                }

                if (this.qualification > 0 && this.qualification <= 50) {
                    return '#f7b924'
                }

                return '#3ac47d'
            }

            value() {
                return this.qualification
            }
        }

        function data() {

            return {

                repositories: @json($repositories),
                repositoryIdAndQualification: @json($repositoryIdAndQualification),

                submiting(repository_id) {
                    console.log(this.$refs['formPDF' + repository_id]);
                    this.$refs['formPDF' + repository_id].submit();
                },

                makeGraph(repository_id) {

                    event.preventDefault();

                    var repo;
                    var qualification;

                    for (var i = 0; i < this.repositoryIdAndQualification.length; i += 1) {
                        repository_clicked = this.repositoryIdAndQualification[i].split(',')[0];
                        console.log(repository_clicked);
                        if (repository_clicked == repository_id) {
                            qualification = new QualificationValueObject(this.repositoryIdAndQualification[i].split(
                                ',')[1]);
                            repo = this.repositoryIdAndQualification[i].split(',')[0];
                        }
                    }

                    color = qualification.color()

                    ctx = document.getElementById('repositoryQualification' + repo);

                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Calificación general', 'nada'],
                            datasets: [{
                                data: [parseFloat(qualification.value()) + 100, 100 - parseFloat(
                                    qualification.value())],
                                backgroundColor: [
                                    color,
                                    'rgba(220, 220, 220, 220)',
                                ],
                                borderColor: [
                                    color,
                                    'rgba(220, 220, 220, 220)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            animation: false,
                            rotation: 1 * Math.PI,
                            circumference: 1 * Math.PI,
                            tooltips: {
                                callbacks: {
                                    label: (tooltipItem, data) => {

                                        return tooltipItem.index == 0 ? "Calificación:" + qualification
                                            .value() + "%" :
                                            ""
                                    }
                                },
                            },
                        },

                    });

                    canvas = document.getElementById('repositoryQualification' + repository_id);
                    canvas.hidden = false;
                    canvas.style.width = "300px";
                    canvas.style.height = "150px";


                    color = qualification.color()

                    ctx = document.getElementById('repositoryQualification' + repo);

                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Calificación general', 'nada'],
                            datasets: [{
                                data: [parseFloat(qualification.value()) + 100, 100 - parseFloat(
                                    qualification.value())],
                                backgroundColor: [
                                    color,
                                    'rgba(220, 220, 220, 220)',
                                ],
                                borderColor: [
                                    color,
                                    'rgba(220, 220, 220, 220)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            animation: false,
                            rotation: 1 * Math.PI,
                            circumference: 1 * Math.PI,
                            tooltips: {
                                callbacks: {
                                    label: (tooltipItem, data) => {

                                        return tooltipItem.index == 0 ? "Calificación:" + qualification
                                            .value() + "%" :
                                            ""
                                    }
                                },
                            },
                        },

                    });

                    //return false;

                    canvas = document.getElementById('repositoryQualification' + repository_id);
                    canvas.hidden = false;
                    canvas.style.width = "500px";
                    canvas.style.height = "250px";



                    se = myChart.toBase64Image();


                    canvas.hidden = true;
                    //a.hidden =true;

                    //submit_value(se);

                    picture = document.getElementById('pic' + repository_id);
                    picture.value = se;
                    console.log(se);

                    setTimeout(0, 2);

                    this.submiting(repository_id);


                },




            }

        }

    </script>

</div>
