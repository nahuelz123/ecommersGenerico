<?php
namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    /**
     * Mostrar las direcciones de envío del usuario.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene todas las direcciones de envío del usuario autenticado
        $addresses = Auth::user()->addresses;

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Mostrar el formulario para agregar una nueva dirección de envío.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Cargar datos necesarios como provincias o ciudades
        $provinces = Province::all(); // Ejemplo, puedes adaptar según tu estructura

        return view('addresses.create', compact('provinces'));
    }

    /**
     * Almacenar una nueva dirección de envío en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'street' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'province_id' => 'required|exists:provinces,id',
        ]);

        // Guardar la nueva dirección
        $address = new ShippingAddress([
            'street' => $request->street,
            'city_id' => $request->city_id,
            'province_id' => $request->province_id,
        ]);

        // Relacionar la dirección con el usuario autenticado
        Auth::user()->shippingAddresses()->save($address);

        return redirect()->route('addresses.index')->with('success', 'Dirección guardada correctamente.');
    }

    /**
     * Mostrar el formulario para editar una dirección de envío.
     *
     * @param  \App\Models\ShippingAddress  $address
     * @return \Illuminate\View\View
     */
    public function edit(ShippingAddress $address)
    {
        // Verificar que la dirección pertenece al usuario autenticado
        if ($address->user_id !== Auth::user()->id) {
            return redirect()->route('addresses.index')->with('error', 'No tienes permiso para editar esta dirección.');
        }

        // Cargar datos necesarios como provincias o ciudades
        $provinces = Province::all();

        return view('addresses.edit', compact('address', 'provinces'));
    }

    /**
     * Actualizar la dirección de envío.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShippingAddress  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ShippingAddress $address)
    {
        // Verificar que la dirección pertenece al usuario autenticado
        if ($address->user_id !== Auth::user()->id) {
            return redirect()->route('addresses.index')->with('error', 'No tienes permiso para actualizar esta dirección.');
        }

        // Validación de los datos recibidos
        $request->validate([
            'street' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'province_id' => 'required|exists:provinces,id',
        ]);

        // Actualizar la dirección
        $address->update([
            'street' => $request->street,
            'city_id' => $request->city_id,
            'province_id' => $request->province_id,
        ]);

        return redirect()->route('addresses.index')->with('success', 'Dirección actualizada correctamente.');
    }

    /**
     * Eliminar una dirección de envío.
     *
     * @param  \App\Models\ShippingAddress  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ShippingAddress $address)
    {
        // Verificar que la dirección pertenece al usuario autenticado
        if ($address->user_id !== Auth::user()->id) {
            return redirect()->route('addresses.index')->with('error', 'No tienes permiso para eliminar esta dirección.');
        }

        // Eliminar la dirección
        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Dirección eliminada correctamente.');
    }
}
