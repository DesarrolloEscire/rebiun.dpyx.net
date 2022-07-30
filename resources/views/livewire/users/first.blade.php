<div class="mb-4">

    @section('header')
    <x-page-title title="Confirmar usuario"
        description="Este módulo permite confirmar y editar la información del usuario."></x-page-title>
    @endsection

    <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-6">
            <x-forms.first :user="$user" />
        </div>
    </div>
</div>
