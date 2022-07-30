<div class="table-responsive shadow bg-white mb-3">
    <table class="table m-0">
        <thead>
            <tr>
                <th nowrap class="text-uppercase" wire:click="sortBy('id')" style="cursor:pointer">
                    @if ($sortBy != 'id')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif($sortDirection == 'asc')
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                    ID
                </th>
                <th nowrap class="text-uppercase" wire:click="sortBy('description')" style="cursor:pointer">
                    @if ($sortBy != 'description')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif($sortDirection == 'asc')
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                    Descripción
                </th>
                <th nowrap class="text-uppercase" wire:click="sortBy('max_punctuation')" style="cursor:pointer">
                    @if ($sortBy != 'max_punctuation')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif($sortDirection == 'asc')
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                    Puntuación
                </th>
                <th nowrap class="text-uppercase" wire:click="sortBy('order')" style="cursor:pointer">
                    @if ($sortBy != 'order')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif($sortDirection == 'asc')
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                    Órden
                </th>
                <th nowrap class="text-uppercase" wire:click="sortBy('category.name')" style="cursor:pointer">
                    @if ($sortBy != 'category.name')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif($sortDirection == 'asc')
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                    Categoría
                </th>
                <th nowrap class="text-uppercase" wire:click="sortBy('subcategory.name')" style="cursor:pointer">
                    @if ($sortBy != 'subcategory.name')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif($sortDirection == 'asc')
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                    Subcategoría
                </th>
                <th nowrap class="text-uppercase">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($questions as $question)
                <tr>
                    <td>{{ $question->id }}</td>
                    <td>{!! str_replace(['\n\r', '\n', '\r'], '<br />', $question->description) !!}</td>
                    <td>Max. {{ $question->max_punctuation }}%</td>
                    <td>{{ $question->order }}</td>
                    <td>{{ $question->category->name }}</td>
                    <td>{{ $question->subcategory->name }}</td>
                    <td class="d-flex justify-content-between">

                        <a href="{{ route('questions.edit', [$question]) }}"
                            class="btn btn-warning btn-shadow rounded-0 mr-1">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-danger btn-shadow rounded-0" data-toggle="modal"
                            data-target="#exampleModalCenter{{ $question->id }}">
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModalCenter{{ $question->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenter{{ $question->id }}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('questions.destroy', [$question]) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-question-circle text-secondary"
                                                    style="font-size: 100px"></i>
                                            </div>
                                            <h2 class="mb-3">
                                                ¿Estás seguro de eliminar la siguiente pregunta?
                                            </h2>
                                            <div class="border p-3 mb-3">
                                                <i>
                                                    {{ $question->description }}
                                                </i>
                                            </div>
                                            <span class="text-muted block mb-3">
                                                Una vez eliminada la pregunta no podrá ser recuperada
                                            </span>
                                            <button type="button" class="btn btn-outline-info" data-dismiss="modal">
                                                <i class="fas fa-times"></i>
                                                cancelar
                                            </button>
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        {{-- <form action="{{ route('questions.destroy', [$question]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
