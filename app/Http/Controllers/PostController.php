<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::publicados()->with('categoria')->latest()->get();

        return view('portada', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    Gate::authorize('create', Post::class);
    return view('avisos.crear', ['categorias' => Categoria::orderBy('nombre')->get()]);
}

public function store(Request $request)
{
    Gate::authorize('create', Post::class);
    $datos = $request->validate([
        'titulo' => ['required', 'max:120'],
        'contenido' => ['required'],
        'categoria_id' => ['required', 'exists:categorias,id'],
    ]);

    $datos['user_id'] = Auth::id();

    Post::create($datos);

    return redirect()->route('avisos.index')->with('ok', 'Aviso guardado');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('update', $post);
        return view('avisos.editar', [
            'post' => $post,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);
        $datos = $request->validate([
            'titulo' => ['required', 'max:120'],
            'contenido' => ['required'],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ]);

        $post->update($datos);

        return redirect()->route('avisos.index')->with('ok', 'Aviso guardado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $post->delete();

        return redirect()->route('avisos.index');
    }
}
