<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.index',compact('user'));
    }

    public function edit()
{
    $user = Auth::user();
    return view('user.edit',compact('user'));
}

public function update(Request $request)
{
    $request->validate([
        'password' => ['required', 'string', 'min:6', 'confirmed'],
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
    ], [
        'password.confirmed' => 'Las contraseñas no coinciden',
    ]);

    $user = Auth::user();

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return redirect()->back()->with('success', 'Datos actualizados correctamente.');
}


    public function createAdmin(Request $request)
    {

    }
}
