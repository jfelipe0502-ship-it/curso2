# Ejercicio 1 · Componentes Blade y un formulario con Tailwind (15 min)

La portada del blog repite las mismas clases en cada tarjeta. Vas a **extraer un componente** para escribirlas una sola vez, y a estilizar un **formulario**, la pieza de UI que más vas a maquetar en la vida real.

## Parte 0 · La portada del blog (la construimos todos juntos en clase)

Estos son los 3 archivos de la portada, completos. En clase los armamos paso a paso con el instructor; aquí están para copiar y pegar, y para ponerte al día si te atoraste o llegaste tarde:

1. `resources/views/layouts/publico.blade.php`:
   ```blade
   <!DOCTYPE html>
   <html lang="es">
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>@yield('titulo', 'Blog de Avisos')</title>
       @vite(['resources/css/app.css', 'resources/js/app.js'])
   </head>
   <body class="bg-gray-100 min-h-screen">
       @yield('contenido')
   </body>
   </html>
   ```

2. `resources/views/portada.blade.php`:
   ```blade
   @extends('layouts.publico')

   @section('titulo', 'Portada · Blog de Avisos')

   @section('contenido')
       <div class="bg-blue-950 text-center py-16 px-8">
           <h1 class="text-4xl font-bold text-white">Blog de Avisos de la Corporación</h1>
           <p class="text-blue-200 mt-3 text-lg">Avisos, operativos y noticias internas</p>
       </div>

       <div class="max-w-4xl mx-auto p-8">
           <div class="grid md:grid-cols-2 gap-4">
               @foreach ($posts as $post)
                   <article class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                       <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full mb-2">
                           {{ $post->categoria }}
                       </span>
                       <h2 class="text-xl font-semibold text-gray-900">{{ $post->titulo }}</h2>
                       <p class="text-gray-600 mt-2">{{ Str::limit($post->contenido, 90) }}</p>
                       <p class="text-gray-400 text-xs mt-4">{{ $post->fecha }}</p>
                   </article>
               @endforeach
           </div>
       </div>
   @endsection
   ```

3. En `routes/web.php`, reemplaza la ruta `/`:
   ```php
   Route::get('/', function () {
       $posts = collect([
           (object) ['titulo' => 'Cambio de horario en barandilla', 'contenido' => 'A partir del lunes el turno nocturno inicia a las 21:00 horas para todo el personal operativo.', 'categoria' => 'Aviso', 'fecha' => '11/08/2026'],
           (object) ['titulo' => 'Curso de primeros auxilios', 'contenido' => 'Inscripciones abiertas en la academia para el curso de primeros auxilios básicos. Cupo limitado.', 'categoria' => 'Capacitación', 'fecha' => '10/08/2026'],
           (object) ['titulo' => 'Mantenimiento de patrullas', 'contenido' => 'Las unidades del sector centro pasan a revisión mecánica esta semana según el rol publicado.', 'categoria' => 'Operativo', 'fecha' => '08/08/2026'],
       ]);

       return view('portada', ['posts' => $posts]);
   });
   ```

   ✅ **Checkpoint 0:** con `composer run dev` corriendo, la portada muestra el hero azul y 3 tarjetas con estilos.

## Parte A · El componente `<x-tarjeta-post>` (7 min)

1. Crea `resources/views/components/tarjeta-post.blade.php`:
   ```blade
   @props(['post'])

   <article class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
       <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full mb-2">
           {{ $post->categoria }}
       </span>
       <h2 class="text-xl font-semibold text-gray-900">{{ $post->titulo }}</h2>
       <p class="text-gray-600 mt-2">{{ Str::limit($post->contenido, 90) }}</p>
       <p class="text-gray-400 text-xs mt-4">{{ $post->fecha }}</p>
   </article>
   ```
   (Los datos de ejemplo traen `categoria` y `fecha` como texto; hoy mismo, cuando el proyecto tenga base de datos en el Ejercicio 2, estas líneas evolucionan a la relación real.)

2. En la portada, reemplaza el `<article>...</article>` del `@foreach` por:
   ```blade
   <x-tarjeta-post :post="$post" />
   ```

   ✅ **Checkpoint A:** la portada se ve idéntica, pero las clases viven en UN archivo. Cambia `rounded-lg` por `rounded-2xl` en el componente: todas las tarjetas cambian a la vez.

## Parte B · Formulario de contacto estilizado (8 min)

3. Crea la vista `resources/views/contacto.blade.php` (extiende `layouts.publico`) con un formulario de: nombre, correo y mensaje. Ruta: `Route::get('/contacto', fn () => view('contacto'));`

4. Estilízalo con este vocabulario (todos los inputs llevan las mismas clases; en el desafío las extraes al componente `<x-campo>`):
   - Contenedor: `max-w-lg mx-auto p-8 bg-white rounded-lg shadow mt-8`
   - Label: `block text-sm font-medium text-gray-700 mb-1`
   - Input/textarea: `w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none`
   - Botón: `w-full bg-blue-900 text-white font-semibold rounded-lg py-2 hover:bg-blue-800 transition`

   ✅ **Checkpoint B:** al hacer clic en un input, el borde y el anillo cambian de color (estados `focus:`).

## 🔥 Desafío (si terminaste antes)

- **Color de marca con `@theme`.** En `resources/css/app.css`:
  ```css
  @theme {
      --color-marca: #1a365d;
  }
  ```
  y ya puedes usar `bg-marca`, `text-marca`, `hover:bg-marca/90` en toda la app. Cámbiale el color al hero y al botón.
- **Estados de error.** Simula la clase de error que conectarás con `@error` en el CRUD de avisos (`05-crud-avisos.md`, en la tarea):
  input inválido = `border-red-500 focus:ring-red-200` + mensaje `text-red-600 text-sm mt-1`.
- Extrae un componente `<x-campo>` con `@props(['label', 'name', 'type' => 'text'])` para no repetir label+input.

## Problemas comunes

| Síntoma | Causa probable |
|---|---|
| `Unable to locate a class or view for component` | El archivo no está en `resources/views/components/` o el nombre no coincide (`tarjeta-post` → `x-tarjeta-post`) |
| El post no llega al componente | Falta `:post="$post"` (con dos puntos; sin ellos pasa el texto literal) |
| `bg-marca` no aplica | El bloque `@theme` va en `app.css` y `composer run dev` debe estar corriendo |
| La página carga sin estilos | Revisa que `composer run dev` siga corriendo en la terminal (es la causa el 90% de las veces) |
