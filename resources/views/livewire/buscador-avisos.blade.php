<div>
    <div x-data="{ abierto: false }" class="mb-6">
        <button
            @click="abierto = !abierto"
            type="button"
            class="rounded-lg bg-blue-900 px-4 py-2 font-semibold text-white hover:bg-blue-800"
        >
            Filtros
        </button>

        <div x-show="abierto" x-cloak class="mt-3 rounded-lg bg-white p-4 shadow">
            <div class="flex flex-col gap-3 md:flex-row">
                <input
                    wire:model.live="busqueda"
                    type="search"
                    placeholder="Buscar aviso"
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                <select
                    wire:model.live="categoriaId"
                    class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
                <button
                    wire:click="limpiar"
                    type="button"
                    class="rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-700 hover:bg-gray-300"
                >
                    Limpiar
                </button>
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($avisos as $aviso)
            <x-tarjeta-post :post="$aviso" />
        @empty
            <p class="text-gray-600">No se encontraron avisos.</p>
        @endforelse
    </div>

    @if ($avisos->hasPages())
        <div class="mt-6">
            {{ $avisos->links() }}
        </div>
    @endif
</div>
