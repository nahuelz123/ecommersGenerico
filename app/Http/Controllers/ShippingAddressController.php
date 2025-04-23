<?php
namespace App\Http\Controllers;

use App\Models\City;
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
        $addresses = Auth::user()->shippingAddresses;
     /*    dd('addresses', $addresses); */
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
    $cities = City::all(); // Ejemplo, puedes adaptar según tu estructura
        return view('addresses.create', compact('provinces', 'cities'));
    }

    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'province_id' => 'required|exists:provinces,id',
            'postal_code' => 'required|string|max:20', 
        ]);
    
        // Verificar si ya existe una dirección con los mismos datos para el usuario autenticado
        $existingAddress = Auth::user()->shippingAddresses()
            ->where('address', $validated['address'])
            ->where('phone', $validated['phone'])
            ->where('city_id', $validated['city_id'])
            ->where('province_id', $validated['province_id'])
            ->where('postal_code', $validated['postal_code'])
            ->first();
    
        // Si ya existe, devolver un mensaje de error
        if ($existingAddress) {
            return redirect()->back()->with('error', 'Esta dirección ya ha sido agregada.');
        }
    
        // Guardar la nueva dirección
        $address = new ShippingAddress([
            'user_id' => Auth::id(),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city_id' => $validated['city_id'],
            'province_id' => $validated['province_id'],
            'postal_code' => $validated['postal_code'], 
        ]);
    
        // Relacionar la dirección con el usuario autenticado
        Auth::user()->shippingAddresses()->save($address);
    
        // Redirigir con mensaje de éxito
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

    public function update(Request $request, $id)
    {
        // Validación de los datos recibidos
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'province_id' => 'required|exists:provinces,id',
            'postal_code' => 'required|string|max:20',
        ]);
    
        // Buscar la dirección a actualizar
        $address = ShippingAddress::findOrFail($id);
    
        // Comprobar si la dirección ya existe, excepto la que estamos actualizando
        if (ShippingAddress::where('user_id', Auth::id())
                          ->where('address', $validated['address'])
                          ->where('city_id', $validated['city_id'])
                          ->where('province_id', $validated['province_id'])
                          ->where('postal_code', $validated['postal_code'])
                          ->where('id', '!=', $address->id) // Ignorar la dirección actual
                          ->exists()) {
            return redirect()->back()->with('error', 'Ya tienes esta dirección registrada.');
        }
    
        // Actualizar la dirección
        $address->update($validated);
    
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
