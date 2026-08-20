# Ejercicio 3 · Etiquetas N:M (nivel 2 · parte B)

Un post puede tener varias etiquetas y una etiqueta puede estar en varios posts: eso es una relación **muchos a muchos**, y se resuelve con una **tabla pivote**. Esto es parte del nivel 2, la meta de la semana: se arranca en clase y se termina de tarea.

> Requisito: haber terminado el nivel 1 del Ejercicio 2 (la parte de scopes ayuda pero no es indispensable).

## Parte A · Migraciones (5 min)

1. Genera dos migraciones:
   ```bash
   php artisan make:migration create_etiquetas_table
   ```
   ```bash
   php artisan make:migration create_etiqueta_post_table
   ```

2. Complétalas:
   - `etiquetas`: `id`, `nombre` (string), timestamps.
   - `etiqueta_post` (la pivote; convención: los dos modelos en **singular y en orden alfabético**, `etiqueta` antes que `post`):
     ```php
     Schema::create('etiqueta_post', function (Blueprint $table) {
         $table->id();
         $table->foreignId('post_id')->constrained()->cascadeOnDelete();
         $table->foreignId('etiqueta_id')->constrained('etiquetas')->cascadeOnDelete();
         $table->timestamps();
     });
     ```

3. Ejecuta: `php artisan migrate`

   ✅ **Checkpoint A:** `php artisan migrate:status` muestra las 2 migraciones en `Ran`.

## Parte B · Modelos y relación (5 min)

4. Crea el modelo `Etiqueta` (`php artisan make:model Etiqueta`) con `$fillable = ['nombre']` y la relación:
   ```php
   public function posts()
   {
       return $this->belongsToMany(Post::class);
   }
   ```

5. En `Post` agrega la relación inversa:
   ```php
   public function etiquetas()
   {
       return $this->belongsToMany(Etiqueta::class);
   }
   ```
   (Laravel deduce solo el nombre de la pivote `etiqueta_post`; por eso importaba la convención.)

## Parte C · attach, sync y consultas (10 min)

6. Crea etiquetas y pruébalas en `php artisan tinker`:
   ```php
   Etiqueta::create(['nombre' => 'seguridad']);
   Etiqueta::create(['nombre' => 'operativo']);
   Etiqueta::create(['nombre' => 'aviso']);

   $post = Post::first();
   $post->etiquetas()->attach([1, 2]);   // engancha seguridad y operativo
   $post->etiquetas->pluck('nombre');    // => ['seguridad', 'operativo']

   Etiqueta::find(1)->posts;             // la relación funciona en ambos sentidos
   $post->etiquetas()->sync([2, 3]);     // reemplaza el set: ahora operativo y aviso
   ```

7. Posts que tengan una etiqueta concreta (consulta sobre la relación):
   ```php
   Post::whereHas('etiquetas', fn ($q) => $q->where('nombre', 'operativo'))->get();
   ```

   ✅ **Checkpoint C:** `attach`, `sync` y `whereHas` te devuelven lo que esperabas y sabes explicar la diferencia entre `attach` y `sync`.

## Qué sigue

Con las etiquetas vivas ya puedes hacer el **Bloque B** del set de consultas de la tarea, y si quieres terreno avanzado (medir el N+1, accessors, soft deletes), sigue con `04-nivel3-avanzado.md`.

## Problemas comunes

| Síntoma | Causa probable |
|---|---|
| `no such table: etiqueta_post` al hacer attach | La pivote no se llama como Laravel espera (singular + orden alfabético) o falta la migración |
| `attach` duplica filas al correrlo dos veces | Es normal: `attach` siempre agrega; usa `sync` para dejar un set exacto |
| `$post->etiquetas` devuelve vacío tras attach | Consultaste la relación cacheada; usa `$post->fresh()->etiquetas` o vuelve a cargar el modelo |
