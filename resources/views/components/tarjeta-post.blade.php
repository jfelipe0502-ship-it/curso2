@props(['post'])

<article class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
    @if ($post->es_nuevo)
        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full mb-2">NUEVO</span>
    @endif
    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full mb-2">
        {{ $post->categoria->nombre }}
    </span>
    <h2 class="text-xl font-semibold text-gray-900">{{ $post->titulo }}</h2>
    <p class="text-gray-600 mt-2">{{ $post->resumen }}</p>
    <p class="text-gray-400 text-xs mt-4">{{ $post->created_at->format('d/m/Y') }}</p>
    
    @can('update', $post)
        <a href="{{ route('avisos.edit', $post) }}" class="text-blue-700 text-sm font-semibold hover:underline mt-4 inline-block">Editar</a>
    @endcan

    @can('delete', $post)
        <form method="POST" action="{{ route('avisos.destroy', $post) }}" class="inline"
              onsubmit="return confirm('¿Borrar este aviso?')">
            @csrf
            @method('DELETE')
            <button class="text-red-600 text-sm font-semibold hover:underline">Borrar</button>
        </form>
    @endcan
</article>
