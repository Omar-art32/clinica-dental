@extends('tablar::page')

@section('content')

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">

            <div class="col">
                <div class="page-pretitle">
                    Administración
                </div>

                <h2 class="page-title">
                    Ajustes
                </h2>
            </div>

            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">

                    <button type="submit" form="form-ajustes" class="btn btn-primary d-none d-sm-inline-block">
                        <i class="ti ti-device-floppy"></i>
                        Guardar Cambios
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <i class="ti ti-checks"></i>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
            <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <form id="form-ajustes" action="{{ route('admin.ajustes.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="card">

                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">
                        Ajustes Generales
                    </h3>
                </div>

                <div class="card-body">

                    <small class="text-muted d-block mb-4">
                        <span class="text-danger">*</span>
                        Campos Requeridos
                    </small>

                    {{-- ===================== INFORMACIÓN GENERAL ===================== --}}
                    <h4 class="mb-3">Información General</h4>

                    <div class="row g-3 mb-4">

                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Nombre
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-building"></i>
                                </span>
                                <input type="text" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    placeholder="Nombre de la Clínica" value="{{ old('nombre', $ajuste?->nombre) }}">
                                @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Divisa -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Divisa
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-currency-dollar"></i>
                                </span>
                                <select name="divisa"
                                    class="form-select @error('divisa') is-invalid @enderror">
                                    <option value="">
                                        Selecciona una divisa
                                    </option>
                                    @foreach ($divisas as $divisa)
                                    <option value="{{ $divisa['symbol'] }}"
                                        {{ old('divisa', $ajuste?->divisa) == $divisa['symbol'] ? 'selected' : '' }}>
                                        {{ $divisa['symbol'] }} - {{ $divisa['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('divisa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="col-12">
                            <label class="form-label">
                                Descripción
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-file-description"></i>
                                </span>
                                <input type="text" name="descripcion"
                                    class="form-control @error('descripcion') is-invalid @enderror"
                                    placeholder="Descripción de la Clínica"
                                    value="{{ old('descripcion', $ajuste?->descripcion) }}">
                                @error('descripcion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- ===================== CONTACTO ===================== --}}
                    <h4 class="mb-3">Contacto</h4>

                    <div class="row g-3">

                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Teléfono
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-phone"></i>
                                </span>
                                <input type="text" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    placeholder="Número de teléfono" value="{{ old('telefono', $ajuste?->telefono) }}">
                                @error('telefono')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Correo electrónico
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-mail"></i>
                                </span>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="correo@ejemplo.com" value="{{ old('email', $ajuste?->email) }}">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sitio web -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Sitio web
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-world"></i>
                                </span>
                                <input type="url" name="web" class="form-control @error('web') is-invalid @enderror"
                                    placeholder="https://ejemplo.com" value="{{ old('web', $ajuste?->web) }}">
                                @error('web')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Dirección
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-map-pin"></i>
                                </span>
                                <textarea name="direccion" rows="1"
                                    class="form-control @error('direccion') is-invalid @enderror"
                                    placeholder="Dirección de la Clínica">{{ old('direccion', $ajuste?->direccion) }}</textarea>
                                @error('direccion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- ===================== LOGO (aislado, no rompe el layout) ===================== --}}
                    <h4 class="mb-3">Logo</h4>

                    <div class="d-flex flex-column align-items-center">

                        <div class="position-relative mb-2">

                            {{-- Avatar de Tabler: si trae <img>, Tabler ya lo recorta a cover dentro del círculo --}}
                            <span id="logo-preview" class="avatar avatar-xl rounded bg-secondary-lt">
                                @if($ajuste?->logo)
                                <img src="{{ asset('storage/' . $ajuste->logo) }}" alt="Logo">
                                @else
                                <i class="ti ti-building fs-1 text-secondary"></i>
                                @endif
                            </span>

                            {{-- Botón flotante para editar/subir, componentes 100% Tabler --}}
                            <label for="logo"
                                class="btn btn-icon btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0"
                                title="Cambiar logo">
                                <i class="ti ti-camera"></i>
                            </label>

                            <input type="file"
                                name="logo"
                                id="logo"
                                class="d-none @error('logo') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/svg+xml">
                        </div>

                        <strong class="small">Logo de la clínica</strong>
                        <small class="form-hint text-center">
                            JPG, PNG o SVG.
                        </small>

                        @error('logo')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>

<script>
    /*** Preview del logo: reutiliza el <img> si ya existe, o lo crea dentro del avatar ***/
    document.getElementById('logo').addEventListener('change', function (event) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const preview = document.getElementById('logo-preview');
        const reader = new FileReader();

        /*** Al terminar de leer el archivo se reemplaza el contenido del avatar por la imagen ***/
        reader.onload = function (e) {
            let img = preview.querySelector('img');

            if (!img) {
                preview.innerHTML = '';
                img = document.createElement('img');
                img.alt = 'Logo';
                preview.appendChild(img);
            }

            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });
</script>

@endsection