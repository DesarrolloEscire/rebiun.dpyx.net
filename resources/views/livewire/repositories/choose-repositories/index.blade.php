<div class="mb-4">
    @section('header')
        <x-page-title title="Selección de repositorios"
            description="Este módulo permite a los evaluadores seleccionar repositorios a evaluar."></x-page-title>
    @endsection
    <div class="mb-3 row d-flext justify-content-between">
        <div class="col-8 col-lg-3">
            <x-input-search />
        </div>
        <div class="mt-4 mb-3 bg-white shadow table-responsive">
            <table class="table m-0 table-bordered">
                <thead>
                    <tr>
                        <th class="text-uppercase">Nombre</th>
                        <th class="text-uppercase">{{ __('containerName') }}</th>
                        <th class="text-uppercase">Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repositories as $repository)
                        @if ($repository->is_selectable_by_user)
                            <tr>
                                <td>
                                    {{ $repository->repository_name }}
                                </td>
                                <td>
                                    {{ $repository->name }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-shadow rounded-0"
                                        {{ $is_admin ? 'disabled' : '' }} data-toggle="modal"
                                        data-target="#attachEvaluator{{ $repository->id }}">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
                                    <x-modals.evaluators.attach :repository="$repository" :evaluator="$evaluator" />
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    {{-- @foreach ($users as $repository)
                        @php
                            $resolutions = \App\Models\StatusResolution::unconciliated()
                                ->whereEvaluation($repository->evaluation)
                                ->get();
                            $ids = $resolutions->pluck('evaluator_id');
                            $repeated = null;
                            
                        @endphp

                        @foreach ($resolutions as $key => $unconciliatedResolution)
                            @php
                                $conciliation = $evaluation->repository->conciliation->id;
                            @endphp
                            @if (isset($authEvaluator) /*|| Auth::user()->is_admin*/)
                                @if ($authEvaluator->id != $ids[0] && $authEvaluator->id != $ids[1] /*|| Auth::user()->is_admin*/)
                                    @if ($unconciliatedResolution->status_conciliation == 'unconciliated')
                                        @if (!$conciliation->evaluator_solve_id)
                                            @if (!($repeated == $unconciliatedResolution->evaluation_id))
                                                <tr>
                                                    <td>
                                                        {{ $unconciliatedResolution->evaluation->repository->repository_name }}
                                                    </td>
                                                    <td>
                                                        {{ $unconciliatedResolution->evaluation->repository->name }}
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger btn-shadow rounded-0"
                                                            {{ $is_admin ? 'disabled' : '' }} data-toggle="modal"
                                                            data-target="#attachEvaluator{{ $unconciliatedResolution->evaluation->repository->id }}">
                                                            <i class="fas fa-clipboard-check"></i>
                                                        </button>
                                                        <x-modals.evaluators.solve :repository="$unconciliatedResolution->evaluation->repository"
                                                            :evaluator="$evaluator" />
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                            @php $repeated = $unconciliatedResolution->evaluation_id; @endphp
                        @endforeach
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
