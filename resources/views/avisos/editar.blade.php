@extends('layouts.publico')

@section('titulo', 'Editar aviso')

@section('contenido')
    <form method="POST" action="{{ route('avisos.update', $post) }}" class="max-w-lg mx-auto p-8 bg-white rounded-lg shadow mt-8">
        @csrf
        @method('PUT')

        @include('avisos._campos')

        <button class="w-full bg-blue-900 text-white font-semibold rounded-lg py-2 hover:bg-blue-800 transition mt-5">
            Guardar cambios
        </button>
    </form>
@endsection