<x-campo label="Título" name="titulo" :value="$post?->titulo ?? ''" />

<label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Categoría</label>
<select id="categoria_id" name="categoria_id"
        class="w-full rounded-lg border px-3 py-2 outline-none @error('categoria_id') border-red-500 focus:ring-red-200 @else border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @enderror">
    <option value="">Elige una categoría...</option>
    @foreach ($categorias as $categoria)
        <option value="{{ $categoria->id }}" @selected(old('categoria_id', $post?->categoria_id) == $categoria->id)>
            {{ $categoria->nombre }}
        </option>
    @endforeach
</select>
@error('categoria_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

<div class="mt-4">
    <x-campo label="Contenido" name="contenido" type="textarea" :value="$post?->contenido ?? ''" />
</div>
