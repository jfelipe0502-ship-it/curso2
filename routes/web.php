<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\Etiqueta;

Route::get('/', function () {
    $posts = Post::with('categoria')->latest()->get();

    return view('portada', ['posts' => $posts]);
});


Route::get('/contacto', fn () => view('contacto'));

