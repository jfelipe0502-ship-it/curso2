<div x-data="{ abierto: false }">
    <button type="button" @click="abierto = !abierto" :aria-expanded="abierto.toString()"
            class="rounded-lg border border-gray-300 px-4 py-2">
        Filtros
    </button>
    <div x-show="abierto" x-cloak class="mt-3">
        <input type="text" wire:model.live.debounce.300ms="busqueda" placeholder="Buscar aviso"
               class="w-full rounded-lg border border-gray-300 px-4 py-2">
    </div>
    <p wire:loading class="text-sm text-gray-500 mt-2">Buscando...</p>
    <div class="grid gap-4 mt-4 md:grid-cols-2">
        @foreach ($avisos as $aviso)
            <x-tarjeta-post :post="$aviso" wire:key="{{ $aviso->id }}" />
        @endforeach
    </div>
</div>
