<form wire:submit="guardar" class="max-w-lg mx-auto p-8 bg-white rounded-lg shadow mt-8">
    <div>
        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
        <input
            id="titulo"
            wire:model.live.debounce.300ms="titulo"
            type="text"
            class="w-full rounded-lg border px-3 py-2 outline-none @error('titulo') border-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @enderror"
        >
        @error('titulo')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div class="mt-4">
        <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
        <select
            id="categoria_id"
            wire:model="categoria_id"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >
            <option value="">Elige una categoría...</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>
        @error('categoria_id')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div class="mt-4">
        <label for="contenido" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
        <textarea
            id="contenido"
            wire:model="contenido"
            rows="6"
            class="w-full rounded-lg border px-3 py-2 outline-none @error('contenido') border-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @enderror"
        ></textarea>
        @error('contenido')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div class="mt-4">
        <label for="resumen" class="block text-sm font-medium text-gray-700 mb-1">Resumen</label>
        <input
            id="resumen"
            wire:model.blur="resumen"
            type="text"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >
        @error('resumen')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
        @enderror
    </div>

    <button
        type="submit"
        wire:loading.attr="disabled"
        class="w-full bg-blue-900 text-white font-semibold rounded-lg py-2 hover:bg-blue-800 transition mt-5 disabled:opacity-50"
    >
        <span wire:loading.remove>Publicar aviso</span>
        <span wire:loading>Guardando...</span>
    </button>
</form>