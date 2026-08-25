@extends('layouts.publico')

@section('titulo', 'Portada · Blog de Avisos')

@section('contenido')
    <div class="bg-marca text-center py-16 px-8">
        <h1 class="text-4xl font-bold text-white">Blog de Avisos de la Corporación</h1>
        <p class="text-blue-200 mt-3 text-lg">Avisos, operativos y noticias internas</p>
    </div>

    <div class="max-w-4xl mx-auto p-8">
        <div class="grid md:grid-cols-2 gap-4">
            @foreach ($posts as $post)
                <x-tarjeta-post :post="$post" />
            @endforeach
        </div>
    </div>
@endsection
