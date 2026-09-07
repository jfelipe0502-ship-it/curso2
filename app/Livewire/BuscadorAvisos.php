<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;

class BuscadorAvisos extends Component
{
    public string $busqueda = '';

    #[On('aviso-guardado')]
    public function refrescar(int $id): void
    {
        // render() vuelve a consultar los avisos cuando llega el evento.
    }

    public function render()
    {
        return view('livewire.buscador-avisos', [
            'avisos' => Post::publicados()
                ->where('titulo', 'like', "%{$this->busqueda}%")
                ->latest()
                ->get(),
        ]);
    }
}
