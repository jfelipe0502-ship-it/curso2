<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FormularioAviso extends Component
{
    #[Validate('required|max:120')]
    public string $titulo = '';

    #[Validate('required')]
    public string $contenido = '';

    #[Validate('required|exists:categorias,id')]
    public string $categoria_id = '';

    #[Validate('nullable|max:160')]
    public string $resumen = '';

    public function guardar(): void
    {
        Gate::authorize('create', Post::class);

        $datos = $this->validate();

        $post = Post::create($datos + ['user_id' => Auth::id()]);

        $this->dispatch('aviso-guardado', id: $post->id);

        $this->redirectRoute('avisos.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.formulario-aviso', [
            'categorias' => \App\Models\Categoria::orderBy('nombre')->get(),
        ]);
    }
}