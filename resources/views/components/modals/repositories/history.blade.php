<div class="modal fade" id="showRepositoryHistory{{ $repository->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalAriaLabelledby" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historial de acciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="border p-2">
                            {{ $repository->name }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive bg-white shadow">
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>
                                            Fecha
                                        </th>
                                        <th>
                                            Usuario
                                        </th>
                                        <th>
                                            Acción
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($repository->historicals as $historical)
                                            <tr>
                                                <td>
                                                    {{ $historical->created_at }}
                                                </td>
                                                <td>
                                                    {{ $historical->user ? $historical->user->name : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $historical->action }}
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-shadow rounded-0" data-dismiss="modal">
                    <i class="fas fa-window-close"></i>
                </button>
                {{-- <button class="btn btn-danger btn-shadow rounded-0"> --}}
                {{-- </button> --}}
            </div>

        </div>
    </div>
</div>
