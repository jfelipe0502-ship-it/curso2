@extends('layouts.publico')

@section('titulo', 'Nuevo aviso')

@section('contenido')
    <form method="POST" action="{{ route('avisos.store') }}" class="max-w-lg mx-auto p-8 bg-white rounded-lg shadow mt-8">
        @csrf

        @include('avisos._campos', ['post' => null])

        <button class="w-full bg-blue-900 text-white font-semibold rounded-lg py-2 hover:bg-blue-800 transition mt-5">
            Publicar aviso
        </button>
    </form>
@endsection