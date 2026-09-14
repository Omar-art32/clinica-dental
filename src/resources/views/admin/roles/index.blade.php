@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Administración</div>
                    <h2 class="page-title">Roles</h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    {{-- TODO: reactivar cuando se cree y asigne el permiso "Guardar roles" --}}
                    {{-- @can('Guardar roles') --}}
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-rol">
                        <i class="ti ti-plus"></i> Nuevo Rol
                    </a>
                    {{-- @endcan --}}
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">

            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">Listado de Roles</h3>
                </div>
                <div class="card-body border-bottom">
                    <form action="{{ route('admin.roles.index') }}" method="get" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label class="form-label mb-0">Buscar rol</label>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}"
                                placeholder="Escribe el nombre del rol">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ti ti-search"></i> Buscar
                            </button>
                            @if (request('buscar'))
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ti ti-x"></i> Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                @if (request('buscar'))
                    <div class="alert alert-info m-3">
                        <i class="ti ti-info-circle"></i>
                        Se encontraron {{ $roles->total() }} resultado(s) para
                        <strong>"{{ request('buscar') }}"</strong>.
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-hover card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Permisos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $rol)
                                <tr>
                                    <td class="text-center">{{ $roles->firstItem() + $loop->index }}</td>
                                    <td>{{ $rol->name }}</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span class="avatar avatar-sm bg-warning-lt text-warning">
                                                <i class="ti ti-lock"></i>
                                            </span>
                                            <div class="lh-sm">
                                                <div class="text-muted small">Permisos Asignados</div>
                                                <div>
                                                    <strong>{{ $rol->permissions_count }}</strong>
                                                    {{ $rol->permissions_count == 1 ? 'Permiso' : 'Permisos' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{-- TODO: reactivar cuando se cree y asigne el permiso "Ver permisos" --}}
                                        {{-- @can('Ver permisos') --}}
                                        <a href="{{ url('admin/roles/' . $rol->id . '/permisos') }}" type="button"
                                            class="btn btn-sm btn-icon btn-warning" title="Permisos">
                                            <i class="ti ti-lock"></i>
                                        </a>
                                        {{-- @endcan --}}

                                        {{-- TODO: reactivar cuando se cree y asigne el permiso "Editar roles" --}}
                                        {{-- @can('Editar roles') --}}
                                        <button type="button" class="btn btn-sm btn-icon btn-success" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modal-rol-edit-{{ $rol->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        {{-- @endcan --}}

                                        {{-- TODO: reactivar cuando se cree y asigne el permiso "Eliminar roles" --}}
                                        {{-- @can('Eliminar roles') --}}
                                        <button type="button" class="btn btn-sm btn-icon btn-danger" title="Eliminar"
                                            data-id="{{ $rol->id }}" data-name="{{ $rol->name }}" data-bs-toggle="modal"
                                            data-bs-target="#modal-rol-delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No hay roles registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    {{ $roles->links('tablar::pagination') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-rol" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('admin.roles.store') }}" method="post">
                @csrf
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Nuevo Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti ti-user-shield"></i>
                            </span>
                            <input type="text"
                                class="form-control @if (session('open_modal') === 'modal-rol' && $errors->has('name')) is-invalid @endif"
                                name="name" value="{{ old('name') }}" placeholder="Ej: admin, doctor">
                        </div>
                        @if (session('open_modal') === 'modal-rol')
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                    {{-- TODO: reactivar cuando se cree y asigne el permiso "Guardar roles" --}}
                    {{-- @can('Guardar roles') --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Crear
                    </button>
                    {{-- @endcan --}}
                </div>
            </form>
        </div>
    </div>

    @foreach ($roles as $rol)
        <div class="modal modal-blur fade modal-edit-rol" id="modal-rol-edit-{{ $rol->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" action="{{ route('admin.roles.update', $rol->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Editar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            style="filter: brightness(0) invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-user-shield"></i>
                                </span>
                                <input type="text"
                                    class="form-control @if (session('open_modal') === 'modal-rol-edit-' . $rol->id && $errors->has('name')) is-invalid @endif"
                                    name="name" data-original-name="{{ $rol->name }}"
                                    value="{{ session('open_modal') === 'modal-rol-edit-' . $rol->id ? old('name', $rol->name) : $rol->name }}"
                                    placeholder="Ej: admin, doctor">
                            </div>
                            @if (session('open_modal') === 'modal-rol-edit-' . $rol->id)
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                        {{-- TODO: reactivar cuando se cree y asigne el permiso "Editar roles" --}}
                        {{-- @can('Editar roles') --}}
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-check"></i> Actualizar
                        </button>
                        {{-- @endcan --}}
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal modal-blur fade" id="modal-rol-delete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form action="" method="post" id="form-delete-rol">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white">Eliminar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            style="filter: brightness(0) invert(1);"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="ti ti-alert-triangle text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3">¿Estás seguro de eliminar el rol <strong id="delete-name"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                        {{-- TODO: reactivar cuando se cree y asigne el permiso "Eliminar roles" --}}
                        {{-- @can('Eliminar roles') --}}
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash"></i> Eliminar
                        </button>
                        {{-- @endcan --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="js-bridge" data-open-modal="{{ session('open_modal') }}" data-roles-base-url="{{ url('admin/roles') }}"
        style="display: none;"></div>
@endsection

@push('js')
    <script>
        function openModal(modalId) {
            var modal = document.getElementById(modalId);
            if (!modal) return;
            modal.classList.add('show');
            modal.style.display = 'block';
            modal.setAttribute('aria-modal', 'true');
            modal.removeAttribute('aria-hidden');

            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
            document.body.classList.add('modal-open');

            function closeModal() {
                modal.classList.remove('show');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                modal.removeAttribute('aria-modal');
                backdrop.remove();
                document.body.classList.remove('modal-open');
            }
            modal.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (el) {
                el.addEventListener('click', closeModal);
            });
            backdrop.addEventListener('click', closeModal);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var bridge = document.getElementById('js-bridge');
            var baseRolesUrl = bridge.getAttribute('data-roles-base-url');
            var openModalId = bridge.getAttribute('data-open-modal');

            document.getElementById('modal-rol-delete').addEventListener('show.bs.modal', function (e) {
                var btn = e.relatedTarget;
                var id = btn.getAttribute('data-id');
                var name = btn.getAttribute('data-name');
                document.getElementById('form-delete-rol').action = baseRolesUrl + '/' + id + '/delete';
                document.getElementById('delete-name').textContent = name;
            });

            function clearFieldError(input) {
                input.classList.remove('is-invalid');
                var wrapper = input.closest('.mb-3');
                var feedback = wrapper ? wrapper.querySelector('small.text-danger') : null;
                if (feedback) {
                    feedback.remove();
                }
            }

            // Modal "Nuevo Rol": al cerrarse (Cancelar o X), limpia el input y el error.
            var modalRol = document.getElementById('modal-rol');
            modalRol.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var input = modalRol.querySelector('input[name="name"]');
                    input.value = '';
                    clearFieldError(input);
                });
            });

            // Modales "Editar Rol": al cerrarse, restaura el nombre original y limpia el error.
            document.querySelectorAll('.modal-edit-rol').forEach(function (modalEl) {
                modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var input = modalEl.querySelector('input[name="name"]');
                        input.value = input.getAttribute('data-original-name');
                        clearFieldError(input);
                    });
                });
            });

            if (openModalId) {
                openModal(openModalId);
            }
        });
    </script>
@endpush