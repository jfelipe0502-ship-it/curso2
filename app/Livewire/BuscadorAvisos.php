<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BuscadorAvisos extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public ?int $categoriaId = null;

    public function mount(?Categoria $categoria = null): void
    {
        $this->categoriaId = $categoria?->id;
    }

    public function updatedBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatedCategoriaId(): void
    {
        $this->resetPage();
    }

    #[On('aviso-guardado')]
    public function refrescar(int $id): void
    {
    }

    public function limpiar(): void
    {
        $this->busqueda = '';
    }

    public function render()
    {
        $consulta = Post::publicados()
            ->where('titulo', 'like', "%{$this->busqueda}%");

        if ($this->categoriaId !== null) {
            $consulta->where('categoria_id', $this->categoriaId);
        }

        return view('livewire.buscador-avisos', [
            'avisos' => $consulta->latest()->paginate(6),
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }
}
