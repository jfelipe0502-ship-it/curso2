<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function mostrar()
    {
        return view('auth.login');
    }

    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'rol' => ['required', 'in:lector,editor'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $usuario = User::create($datos);
        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function entrar(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($datos)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()
            ->withErrors(['email' => 'Esas credenciales no coinciden con nuestros registros.'])
            ->onlyInput('email');
    }

    public function salir(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}