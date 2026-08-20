# Ejercicio 4 · Nivel 3: N+1, accessors y soft deletes (avanzado, extra)

Tres herramientas de quien mantiene sistemas reales: **medir** un problema de rendimiento, **calcular** datos desde el modelo y **borrar sin perder**. Es extra: cuenta a tu favor en la revisión del PR, pero no es requisito.

> Requisitos: niveles 1 y 2 terminados (portada con BD, scopes y etiquetas).

## Parte A · Caza el N+1 con evidencia (10 min)

El problema: pedir los posts (1 query) y luego, al recorrerlos, disparar UNA query extra por cada post para traer su categoría. Con 8 posts ni se nota; con 2,000 la página se arrastra.

1. En `php artisan tinker`, mide las dos versiones:
   ```php
   DB::enableQueryLog();
   Post::all()->each(fn ($p) => $p->categoria->nombre);
   count(DB::getQueryLog());     // 1 + N: una query por post

   DB::flushQueryLog();
   Post::with('categoria', 'etiquetas')->get()->each(fn ($p) => $p->categoria->nombre);
   count(DB::getQueryLog());     // 3 en total, sin importar cuántos posts
   ```

2. Mira una query del log para ver qué hizo `with()` por dentro: `DB::getQueryLog()[1];` (un solo `select ... where categoria_id in (...)`).

   ✅ **Checkpoint A:** puedes decir cuántas queries corre tu portada hoy y por qué `with('categoria')` la salva.

## Parte B · Accessor `resumen` (10 min)

Hoy la tarjeta corta el texto con `Str::limit($post->contenido, 90)`: lógica de presentación regada en la vista. Un **accessor** la centraliza en el modelo como propiedad calculada.

3. En `app/Models/Post.php`:
   ```php
   use Illuminate\Database\Eloquent\Casts\Attribute;
   use Illuminate\Support\Str;

   protected function resumen(): Attribute
   {
       return Attribute::get(
           fn () => Str::limit($this->contenido, 90)
       );
   }
   ```

4. En `<x-tarjeta-post>`, reemplaza `Str::limit($post->contenido, 90)` por:
   ```blade
   {{ $post->resumen }}
   ```

   ✅ **Checkpoint B:** la portada se ve igual, pero si mañana el resumen cambia a 120 caracteres, se cambia en UN lugar. Bonus para explicar en tu PR: `created_at` ya te llegaba como fecha por un **cast** automático (por eso funciona `->format()`), y tú agregaste otro con `'publicado' => 'boolean'`.

   > **La otra sintaxis (la vas a encontrar en código existente):** en versiones anteriores de Laravel los accessors se escriben con prefijo: `public function getResumenAttribute()` (y el espejo `setTituloAttribute($valor)` para guardar). En Laravel 12 funcionan las DOS y producen el mismo `$post->resumen`; mucho código en producción usa la clásica, así que conviene saber leerla.

   **Reto extra · campo calculado a partir de VARIAS columnas:** crea el accessor `esNuevo` que combine `publicado` y `created_at`:
   ```php
   protected function esNuevo(): Attribute
   {
       return Attribute::get(fn () =>
           $this->publicado
           && $this->created_at->gt(now()->subDays(7))
       );
   }
   ```
   y en la tarjeta, un badge condicional:
   ```blade
   @if ($post->es_nuevo)
       <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full mb-2">NUEVO</span>
   @endif
   ```
   ✅ Los avisos de los últimos 7 días estrenan badge; el campo `es_nuevo` no existe en la tabla, nace de otras dos columnas.

## Parte C · Soft deletes: borrar sin perder (10 min)

5. Migración y trait:
   ```bash
   php artisan make:migration add_deleted_at_to_posts_table
   ```
   ```php
   public function up(): void
   {
       Schema::table('posts', function (Blueprint $table) {
           $table->softDeletes();   // crea la columna deleted_at
       });
   }

   public function down(): void
   {
       Schema::table('posts', function (Blueprint $table) {
           $table->dropSoftDeletes();
       });
   }
   ```
   ```bash
   php artisan migrate
   ```

6. En el modelo `Post`:
   ```php
   use Illuminate\Database\Eloquent\SoftDeletes;

   class Post extends Model
   {
       use SoftDeletes;
       // ...
   }
   ```

7. Pruébalo en Tinker:
   ```php
   $antes = Post::count();
   Post::first()->delete();          // NO borra: marca deleted_at
   Post::count();                    // uno menos
   Post::onlyTrashed()->get();       // la papelera
   Post::withTrashed()->first()->restore();
   Post::count() === $antes;         // => true, volvió
   ```

   ✅ **Checkpoint C:** borraste un post, la portada lo dejó de mostrar, lo restauraste y regresó. El dato nunca se fue de la tabla.

## Problemas comunes

| Síntoma | Causa probable |
|---|---|
| `count(DB::getQueryLog())` da 0 | Se te olvidó `DB::enableQueryLog()` antes de consultar (o Tinker se reinició) |
| `Property [resumen] does not exist` | El método debe llamarse `resumen()` (camelCase de la propiedad), regresar `Attribute` y NO ser público estático; revisa los `use` |
| `delete()` borra de verdad | Falta el trait `SoftDeletes` en el modelo (la migración sola no basta) |
| `Call to undefined method dropSoftDeletes` | Escríbelo dentro de `Schema::table('posts', ...)`, no de `Schema::create` |
