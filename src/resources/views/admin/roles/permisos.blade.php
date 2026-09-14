@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Administración</div>
                    <h2 class="page-title">Permisos del rol</h2>
                    <div class="text-muted mt-1">
                        Rol: <span class="badge bg-teal-lt">{{ $role->name }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">
                        <i class="ti ti-arrow-left"></i> Volver a Roles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @php
                $grupos = $permissions
                    ->groupBy(function ($p) {
                        $palabras = explode(' ', $p->name);
                        return ucfirst(strtolower(end($palabras)));
                    })
                    ->sortKeys();
            @endphp

            <div class="row row-cards">
                @foreach ($grupos as $grupo => $perms)
                    @php
                        $perms = $perms->sortBy('name');
                        $activos = $perms
                            ->filter(function ($p) use ($rolePermissions) {
                                return in_array($p->id, $rolePermissions);
                            })
                            ->count();
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title text-white">{{ $grupo }}</h3>
                                <div class="ms-auto">
                                    <span class="badge bg-white text-primary">{{ $activos }} /
                                        {{ $perms->count() }}</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach ($perms as $perm)
                                        <label class="list-group-item d-flex align-items-center px-3 py-2">
                                            <div class="me-auto">{{ $perm->name }}</div>
                                            <div class="form-check form-switch ms-3 mb-0">
                                                <input class="form-check-input permiso-toggle" type="checkbox"
                                                    style="cursor:pointer" data-id="{{ $perm->id }}"
                                                    {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const TOGGLE_URL = "{{ route('admin.roles.togglePermisos', $role->id) }}";

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.permiso-toggle').forEach(function(el) {
                el.addEventListener('change', function() {
                    const input = el;
                    const wasChecked = input.checked;
                    const id = input.dataset.id;

                    input.disabled = true;

                    fetch(TOGGLE_URL, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                permission_id: id
                            })
                        })
                        .then(function(res) {
                            if (!res.ok) {
                                throw new Error('HTTP ' + res.status);
                            }
                            return res.json();
                        })
                        .then(function(data) {
                            if (!data.success) {
                                throw new Error('fail');
                            }
                            input.checked = data.state;

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.state ? 'Permiso otorgado' :
                                    'Permiso revocado',
                                timer: 2500,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                        })
                        .catch(function() {
                            input.checked = !wasChecked;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo actualizar el permiso. Inténtalo de nuevo.',
                                confirmButtonText: 'Aceptar'
                            });
                        })
                        .finally(function() {
                            input.disabled = false;
                        });
                });
            });
        });
    </script>
@endpush