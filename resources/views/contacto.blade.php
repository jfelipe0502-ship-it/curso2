@extends('layouts.publico')

@section('titulo', 'Contacto · Blog de Avisos')

@section('contenido')
    <form method="GET" action="#" class="max-w-lg mx-auto p-8 bg-white rounded-lg shadow mt-8">
        <x-campo label="Nombre" name="nombre" />

        <div class="mt-4">
            <x-campo label="Correo" name="correo" type="email" />
        </div>

        <div class="mt-4">
            <x-campo label="Mensaje" name="mensaje" type="textarea" />
        </div>

        <button type="submit" class="w-full bg-marca text-white font-semibold rounded-lg py-2 hover:bg-marca/90 transition mt-5">
            Enviar mensaje
        </button>
    </form>
@endsection
