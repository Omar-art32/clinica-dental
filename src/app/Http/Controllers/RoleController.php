<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Muestra el listado de roles registrados.
     *
     * Permite buscar roles por nombre y muestra 10 registros
     * por página. También obtiene la cantidad de permisos
     * asignados a cada rol.
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $roles = Role::withCount('permissions')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('name', 'like', "%{$buscar}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo rol.
     *
     * Actualmente no se utiliza porque el formulario
     * de creación se encuentra dentro de un modal.
     */
    public function create()
    {
        //
    }

    /**
     * Guarda un nuevo rol en la base de datos.
     *
     * Valida que el nombre sea obligatorio, sea una cadena,
     * no supere los 255 caracteres y no esté registrado previamente.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
        ], [
            'name.unique' => 'Ya existe un rol con ese nombre.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.roles.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'modal-rol');
        }

        $role = new Role();
        $role->name = strtoupper($request->name);
        $role->save();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol creado correctamente');
    }

    /**
     * Muestra la información de un rol específico.
     *
     * Actualmente no se utiliza porque la vista principal
     * concentra las operaciones de administración de roles.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Muestra el formulario para editar un rol.
     *
     * Actualmente no se utiliza porque la edición se realiza
     * mediante un modal dentro de la vista principal.
     */
    public function edit(string $id)
    {
        //
    }


    /**
     * Actualiza un rol existente.
     *
     * Valida que el nombre sea obligatorio, sea una cadena,
     * no supere los 255 caracteres y no esté registrado en OTRO rol
     * (se excluye el propio rol que se está editando).
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ], [
            'name.unique' => 'Ya existe un rol con ese nombre.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.roles.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'modal-rol-edit-' . $role->id);
        }

        $role->name = $request->name;
        $role->save();

        return redirect()
            ->route('admin.roles.index')

            ->with('success', 'Rol actualizado correctamente');
    }

    /**
     * Elimina un rol de la base de datos.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado correctamente');
    }

    /**
     * Muestra la administración de permisos de un rol.
     *
     * Carga todos los permisos disponibles en el sistema y los IDs
     * de los permisos actualmente asignados al rol, para que la vista
     * pueda marcar los switches correspondientes como activos.
     */
    public function permisos($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::orderBy('name')->get();

        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.roles.permisos', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Asigna o retira un permiso de un rol (toggle).
     *
     * Recibe el ID del permiso vía JSON, verifica si el rol ya lo
     * tiene y hace lo contrario: lo asigna si no lo tiene, lo retira
     * si ya lo tiene. Devuelve el nuevo estado para que el frontend
     * sincronice el switch sin recargar la página.
     */
    public function togglePermisos(Request $request, $id)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);
        $permission = Permission::findOrFail($request->permission_id);

        $tienePermiso = $role->hasPermissionTo($permission);

        if ($tienePermiso) {
            $role->revokePermissionTo($permission);
            $nuevoEstado = false;
        } else {
            $role->givePermissionTo($permission);
            $nuevoEstado = true;
        }

        return response()->json([
            'success' => true,
            'state' => $nuevoEstado,
        ]);
    }
}
