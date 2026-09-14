<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use Illuminate\Http\Request;

class AjusteController extends Controller
{
    /**
     * Muestra la página de ajustes generales.
     *
     * Obtiene el primer registro de la tabla ajustes y carga
     * las divisas disponibles desde el archivo divisas.json.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene el primer registro de configuración de la clínica.
        $ajuste = Ajuste::first();

        // Ruta del archivo que contiene las divisas disponibles.
        $path = public_path('divisas.json');

        // Lee y decodifica el archivo JSON si existe.
        $decoded = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        // Obtiene los datos del JSON.
        $items = $decoded['data'] ?? $decoded;

        // Filtra únicamente las divisas que tengan símbolo y nombre.
        $divisas = array_values(
            array_filter(
                is_array($items) ? $items : [],
                fn($item) =>
                is_array($item)
                && isset($item['symbol'], $item['name'])
            )
        );

        return view(
            'admin.ajustes.index',
            compact('divisas', 'ajuste')
        );
    }

    /**
     * Muestra el formulario para crear un nuevo ajuste.
     *
     * Actualmente no se utiliza porque la configuración
     * se administra desde la vista principal de ajustes.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Guarda la configuración de la clínica.
     *
     * Valida los datos recibidos desde el formulario y crea
     * un nuevo registro en la tabla ajustes.
     *
     * Si se proporciona un logo, se almacena en el directorio
     * público destinado a los logos de la clínica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valida los datos enviados desde el formulario.
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'direccion' => ['required', 'string'],
            'telefono' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'divisa' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'web' => ['nullable', 'url', 'max:255'],
        ]);

        // Guarda el logo si el usuario seleccionó uno.
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Obtiene el registro existente o crea uno nuevo.
        $ajuste = Ajuste::first() ?? new Ajuste();

        // Guarda todos los datos validados.
        $ajuste->fill($validated);
        $ajuste->save();

        // Regresa a la página de ajustes mostrando un mensaje de éxito.
        return redirect()
            ->route('admin.ajustes.index')
            ->with('swal',[
                'icon' => 'success',
                'title' => '¡Configuración guardada!',
                'text' => 'Los ajustes de la clínica se han guardado correctamente.',
            ] );
    }
    /**
     * Muestra un ajuste específico.
     *
     * Actualmente no se utiliza porque la configuración
     * se administra desde la página principal de ajustes.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return void
     */
    public function show(Ajuste $ajuste)
    {
        //
    }

    /**
     * Muestra el formulario para editar un ajuste existente.
     *
     * Actualmente no se utiliza porque la edición se realiza
     * directamente desde la página principal de ajustes.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return void
     */
    public function edit(Ajuste $ajuste)
    {
        //
    }

    /**
     * Actualiza un ajuste existente.
     *
     * Actualmente no se utiliza. La lógica de actualización
     * puede implementarse posteriormente para evitar crear
     * múltiples registros de configuración.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ajuste  $ajuste
     * @return void
     */
    public function update(Request $request, Ajuste $ajuste)
    {
        //
    }

    /**
     * Elimina un ajuste existente.
     *
     * Actualmente no se utiliza porque la configuración de
     * la clínica debe mantenerse disponible en el sistema.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return void
     */
    public function destroy(Ajuste $ajuste)
    {
        //
    }
}