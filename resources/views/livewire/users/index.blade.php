<div class="mb-4">

    @section('header')
        <x-page-title title="Lista de usuarios"
            description="Este módulo permite ver la información de los usuarios actualmente registrados."></x-page-title>
    @endsection

    @if (!$unverifiedUsers->isEmpty())
        <h3 class="mb-4">
            SOLICITUDES DE USUARIOS
        </h3>

        <div class="table-responsive shadow mb-3 bg-white">
            <table class="table">
                <thead>
                    <tr>
                        <th>NOMBRE</th>
                        <th>CORREO</th>
                        <th>REPOSITORIO</th>
                        <th>ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unverifiedUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>📧 {{ $user->email }}</td>
                            <td>{{ $user->repositories()->first()->name }}</td>
                            <td>

                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-shadow rounded-0 btn-danger" data-toggle="modal"
                                    data-target="#exampleModalCenter{{ $user->id }}">
                                    <i class="fas fa-times"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalCenter{{ $user->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">rechazar solicitud
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <form
                                                                    action="{{ route('users.requests.reject', [$user]) }}"
                                                                    method="POST">
                                                                    <label for="" class="text-muted">CUERPO DEL
                                                                        CORREO</label>
                                                                    <textarea name="email_body" id="" cols="30" rows="5"
                                                                        class="form-control mb-3">Tu solicitud no ha sido aceptada.</textarea>
                                                                    @csrf
                                                                    <button class="btn btn-shadow rounded-0 btn-danger"
                                                                        data-toggle="tooltip" title="Rechazar">
                                                                        <i class="fas fa-times"></i> rechazar
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-shadow rounded-0 btn-success" data-toggle="modal"
                                    data-target="#acceptUserRequestModal-{{ $user->id }}">
                                    <i class="fas fa-check"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="acceptUserRequestModal-{{ $user->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 p-4 text-center">
                                                        <form action="{{ route('users.requests.accept', [$user]) }}"
                                                            method="POST">
                                                            <i class="fas fa-question-circle" style="font-size: 130px"></i>
                                                            <h2 class="my-2">¿Estás seguro de aceptar al usuario?</h2>
                                                            <p>
                                                                
                                                                Tendrá acceso a su cuenta y podrá responder su
                                                                evaluación.
                                                            </p>
                                                            @csrf
                                                            <form
                                                                action="{{ route('users.requests.accept', [$user]) }}"
                                                                method="POST" class="d-inline-block text-center">
                                                                @csrf
                                                                <div class="text-center">
                                                                    <button class="btn btn-shadow rounded-0 btn-success"
                                                                        data-toggle="tooltip" title="Aceptar">
                                                                        <i class="fas fa-check"></i> sí, aceptar
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif

    <div class="row d-flext justify-content-between mb-3">
        <div class="col-12 col-lg-4">
            <x-input-search />
        </div>
        <div class="col-12 col-lg-4">
            @can('create users')
                <a href="{{ route('users.create') }}" class="btn btn-success btn-wide btn-shadow rounded-0 float-right">
                    <i class="fas fa-plus"></i> Añadir
                </a>
            @endcan
        </div>
    </div>

    <div class="table-responsive shadow mb-3 bg-white">
        <table id="table" class="table table-bordered m-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-uppercase">Nombre</th>
                    <th class="text-uppercase">Correo</th>
                    <th class="text-uppercase">Teléfono</th>
                    <th class="text-uppercase">Rol</th>
                    @canany(['delete users', 'edit users'])
                        <th class="text-uppercase">Acciones</th>
                    @endcanany
                    <th class="text-uppercase">Último acceso</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            {{-- <div class="mb-2 mr-2 badge badge-{{$user->role_color}}">{{$user->roles()->first()->name}}</div> --}}
                            <div class="mb-2 mr-2 badge badge-dot badge-dot-xl badge-{{ $user->role_color }}">
                                {{ $user->role_color }}</div>
                            <span>{{ $user->roles()->first() ? $user->roles()->first()->name : '' }}</span>
                        </td>
                        @canany(['delete users', 'edit users'])
                            <td>
                                @can('edit users')
                                    <a href="{{ route('users.edit', [$user]) }}"
                                        class="btn btn-warning rounded-0 btn-shadow">
                                        <span data-toggle="tooltip" title="Editar" wire:ignore.self>
                                            <i class="fas fa-pencil-alt"></i>
                                        </span>
                                    </a>
                                @endcan
                                @can('delete users')
                                    <x-modals.users.delete :user="$user" />
                                @endcan
                            </td>
                        @endcanany
                        <td class="{{ $user->is_active ? 'text-success' : 'text-danger' }}">
                            {{ $user->last_login_at ?? 'Nunca' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-4">
        {{ $users->links() }}
    </div>

    {{-- {{dd($unverifiedUsers->empty())}} --}}




</div>
