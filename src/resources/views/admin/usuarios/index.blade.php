
@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Administración</div>
                    <h2 class="page-title">Usuarios</h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    @can('Guardar usuarios')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-usuario">
                            <i class="ti ti-plus"></i> Nuevo Usuario
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">Listado de Usuarios</h3>
                </div>
                <div class="card-body border-bottom">
                    <form action="{{ route('admin.usuarios.index') }}" method="get" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label class="form-label mb-0">Buscar usuario</label>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}"
                                placeholder="Escribe el nombre o email">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ti ti-search"></i> Buscar
                            </button>
                            @if (request('buscar'))
                                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ti ti-x"></i> Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                @if (request('buscar'))
                    <div class="alert alert-info m-3">
                        <i class="ti ti-info-circle"></i>
                        Se encontraron {{ $users->total() }} resultado(s) para
                        <strong>"{{ request('buscar') }}"</strong>.
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-hover card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Rol</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $users->firstItem() + $loop->index }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span
                                            class="badge bg-primary text-white">{{ $user->roles->first()->name ?? 'Sin rol' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $user->estado === 'activo' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ $user->estado }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @can('Editar usuarios')
                                            <button type="button" class="btn btn-sm btn-icon btn-success" title="Editar"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-usuario-edit-{{ $user->id }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                        @endcan
                                        @can('Eliminar usuarios')
                                            <button type="button" class="btn btn-sm btn-icon btn-danger" title="Eliminar"
                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                data-bs-toggle="modal" data-bs-target="#modal-usuario-delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No hay usuarios registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    {{ $users->links('tablar::pagination') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear --}}
    <div class="modal modal-blur fade" id="modal-usuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('admin.usuarios.store') }}" method="post">
                @csrf
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-user"></i></span>
                                <input type="text"
                                    class="form-control @if (session('open_modal') === 'modal-usuario' && $errors->has('name')) is-invalid @endif" name="name"
                                    required value="{{ old('name') }}" placeholder="Nombre completo">
                            </div>
                            @if (session('open_modal') === 'modal-usuario')
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                <input type="email"
                                    class="form-control @if (session('open_modal') === 'modal-usuario' && $errors->has('email')) is-invalid @endif" name="email"
                                    required value="{{ old('email') }}" placeholder="Correo electronico">
                            </div>
                            @if (session('open_modal') === 'modal-usuario')
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                                <input type="password"
                                    class="form-control @if (session('open_modal') === 'modal-usuario' && $errors->has('password')) is-invalid @endif"
                                    name="password" required placeholder="Minimo 6 caracteres">
                            </div>
                            @if (session('open_modal') === 'modal-usuario')
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                                <input type="password"
                                    class="form-control @if (session('open_modal') === 'modal-usuario' && $errors->has('password_confirmation')) is-invalid @endif"
                                    name="password_confirmation" required placeholder="Repetir contraseña">
                            </div>
                            @if (session('open_modal') === 'modal-usuario')
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" name="rol" required>
                                <option value="">Seleccionar rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('rol') === $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select @if (session('open_modal') === 'modal-usuario' && $errors->has('estado')) is-invalid @endif"
                                name="estado" required>
                                <option value="activo" {{ old('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo
                                </option>
                            </select>
                            @if (session('open_modal') === 'modal-usuario')
                                @error('estado')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endif
                            <small class="text-muted">Activo: puede iniciar sesion</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                    @can('Guardar usuarios')
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Crear
                        </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Editar --}}
    @foreach ($users as $user)
        <div class="modal modal-blur fade" id="modal-usuario-edit-{{ $user->id }}" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" action="{{ route('admin.usuarios.update', $user->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            style="filter: brightness(0) invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                                    <input type="text"
                                        class="form-control @if (session('open_modal') === 'modal-usuario-edit-' . $user->id && $errors->has('name')) is-invalid @endif"
                                        name="name" required
                                        value="{{ session('open_modal') === 'modal-usuario-edit-' . $user->id ? old('name', $user->name) : $user->name }}"
                                        placeholder="Nombre completo">
                                </div>
                                @if (session('open_modal') === 'modal-usuario-edit-' . $user->id)
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                    <input type="email"
                                        class="form-control @if (session('open_modal') === 'modal-usuario-edit-' . $user->id && $errors->has('email')) is-invalid @endif"
                                        name="email" required
                                        value="{{ session('open_modal') === 'modal-usuario-edit-' . $user->id ? old('email', $user->email) : $user->email }}"
                                        placeholder="Correo electronico">
                                </div>
                                @if (session('open_modal') === 'modal-usuario-edit-' . $user->id)
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-lock"></i></span>
                                    <input type="password"
                                        class="form-control @if (session('open_modal') === 'modal-usuario-edit-' . $user->id && $errors->has('password')) is-invalid @endif"
                                        name="password" placeholder="Nueva contraseña">
                                </div>
                                <small class="text-muted">Dejar vacio para no cambiar</small>
                                @if (session('open_modal') === 'modal-usuario-edit-' . $user->id)
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-lock"></i></span>
                                    <input type="password"
                                        class="form-control @if (session('open_modal') === 'modal-usuario-edit-' . $user->id && $errors->has('password_confirmation')) is-invalid @endif"
                                        name="password_confirmation" placeholder="Repetir contraseña">
                                </div>
                                @if (session('open_modal') === 'modal-usuario-edit-' . $user->id)
                                    @error('password_confirmation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Rol <span class="text-danger">*</span></label>
                                <select class="form-select" name="rol" required>
                                    <option value="">Seleccionar rol</option>
                                    @foreach ($roles as $role)
                                        @php
                                            $selectedRole =
                                                session('open_modal') === 'modal-usuario-edit-' . $user->id
                                                    ? old('rol', $user->roles->first()->name ?? '')
                                                    : $user->roles->first()->name ?? '';
                                        @endphp
                                        <option value="{{ $role->name }}"
                                            {{ $selectedRole === $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @if (session('open_modal') === 'modal-usuario-edit-' . $user->id && $errors->has('estado')) is-invalid @endif"
                                    name="estado" required>
                                    <option value="activo"
                                        {{ (session('open_modal') === 'modal-usuario-edit-' . $user->id ? old('estado', $user->estado) : $user->estado) === 'activo' ? 'selected' : '' }}>
                                        Activo</option>
                                    <option value="inactivo"
                                        {{ (session('open_modal') === 'modal-usuario-edit-' . $user->id ? old('estado', $user->estado) : $user->estado) === 'inactivo' ? 'selected' : '' }}>
                                        Inactivo</option>
                                </select>
                                @if (session('open_modal') === 'modal-usuario-edit-' . $user->id)
                                    @error('estado')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                        @can('Editar usuarios')
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i> Actualizar
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Modal Eliminar --}}
    <div class="modal modal-blur fade" id="modal-usuario-delete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form action="" method="post" id="form-delete-usuario">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white">Eliminar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            style="filter: brightness(0) invert(1);"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="ti ti-alert-triangle text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3">¿Estás seguro de eliminar al usuario <strong id="delete-name"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                        @can('Eliminar usuarios')
                            <button type="submit" class="btn btn-danger">
                                <i class="ti ti-trash"></i> Eliminar
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
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
            modal.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(el) {
                el.addEventListener('click', closeModal);
            });
            backdrop.addEventListener('click', closeModal);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modal-usuario-delete').addEventListener('show.bs.modal', function(e) {
                var btn = e.relatedTarget;
                var id = btn.getAttribute('data-id');
                var name = btn.getAttribute('data-name');
                document.getElementById('form-delete-usuario').action = '{{ url('admin/usuarios') }}/' +
                    id + '/delete';
                document.getElementById('delete-name').textContent = name;
            });

            @if (session('open_modal'))
                openModal('{{ session('open_modal') }}');
            @endif
        });
    </script>
@endpush