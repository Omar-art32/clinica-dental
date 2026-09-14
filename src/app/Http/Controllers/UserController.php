<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // va abrir una vista
        $buscar = $request->buscar;
        $users = User::withCount('roles')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('name', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%");
            })
            ->paginate(10)
            ->withQueryString();


        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.usuarios.index', compact('users', 'roles', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
