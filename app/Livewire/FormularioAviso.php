<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FormularioAviso extends Component
{
    #[Validate('required|max:120')]
    public string $titulo = '';

    #[Validate('required')]
    public string $contenido = '';

    #[Validate('required')]
    public string $categoria_id = '';

    #[Validate('nullable|max:160')]
    public string $resumen = '';

    protected function rules(): array
    {
        return [
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id')->whereIn('nombre', Categoria::nombresPermitidosParaAvisos()),
            ],
        ];
    }

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
            'categorias' => Categoria::permitidasParaAvisos()->orderBy('nombre')->get(),
        ]);
    }
}