<div class="modal fade" id="SendConciliation" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalAriaLabelledby" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar calificación</h5>
                <button wire:click='startPoll()' type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('status.store', [$repository])}}" method="POST" class="d-inline">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12">
                            <div class="alert alert-info fade show" role="alert">
                                <h4 class="alert-heading">¡Estas seguro!</h4>
                                <p>
                                    <div>
                                    Estas a punto de enviar un nuevo status de dictamen, para este punto ya deberias de haber llegado a un acuerdo con tu contraparte lo mejor seria calificar igual.
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden"  name="response" value="{{$response}}" >
                <div class="modal-footer">
                    <div x-data="{ buttonDisabled: false }">
                    <button x-on:click="buttonDisabled = true" x-bind:disabled="buttonDisabled" id="btnfrmSend"
                        class="btn btn-success btn-shadow rounded-0">
                        Enviar Status
                    </button>
                    </div>


                </div>
            </form>

        </div>
    </div>
</div>
