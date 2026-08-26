@extends('layouts.publico')

@section('titulo', 'Crear cuenta')

@section('contenido')
    <form method="POST" action="{{ route('registro') }}" class="max-w-sm mx-auto p-8 bg-white rounded-lg shadow mt-12">
        @csrf

        <h1 class="text-xl font-semibold text-gray-900 mb-4">Crear una cuenta</h1>

        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Correo</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

        <label for="rol" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Rol</label>
        <select id="rol" name="rol" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
            <option value="lector" @selected(old('rol', 'lector') === 'lector')>Lector</option>
            <option value="editor" @selected(old('rol') === 'editor')>Editor</option>
        </select>
        @error('rol') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Contraseña</label>
        <input id="password" name="password" type="password" required
               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Confirmar contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">

        <button type="submit" class="w-full bg-blue-900 text-white font-semibold rounded-lg py-2 hover:bg-blue-800 transition mt-5">
            Registrarme
        </button>

        <a href="{{ route('login') }}" class="block w-full bg-blue-900 text-white text-center font-semibold rounded-lg py-2 hover:bg-blue-800 transition mt-4">
            Ya tengo una cuenta
        </a>
    </form>
@endsection