<div class="modal fade" id="attachEvaluator{{$repository->id}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalAriaLabelledby" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar repositorio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('evaluators.solve')}}" method="GET" class="d-inline">
                @csrf
                @method('GET')
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12">
                            <div class="alert alert-info fade show" role="alert">
                                <h4 class="alert-heading">¡Confirma!</h4>
                                <p>
                                    <div>
                                    Este es un repositorio que ya paso por una revisión, tu desición será unica y al agregarlo ya no es posible dar marcha atras, ¿Deseas agregar este repositorio?
                                 <div>
                                    <div class="text-dark">{{ $repository->name }}</div> a nombre de <div class="text-dark">{{$repository->repository_name}}</div>
                                    <input type="hidden" name="evaluator_id" value="{{$evaluator->id}}">
                                    <input type="hidden" name="repository_id" value="{{$repository->id}}">
                                 </div>
                                </div>
                                </p>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <div x-data="{ buttonDisabled: false }">
                    <button x-on:click="buttonDisabled = true" x-bind:disabled="buttonDisabled" id="btnfrm{{$repository->id}}"
                        class="btn btn-success btn-shadow rounded-0">
                        <i class="fas fa-clipboard-check"></i>
                    </button>
                    </div>


                </div>
            </form>

        </div>
    </div>
</div>
