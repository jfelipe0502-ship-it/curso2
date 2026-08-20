# Ejercicio 2 · Nace la base de datos del blog (20 min en clase + tarea)

Hasta hoy la portada muestra objetos inventados en la ruta. En este ejercicio el blog estrena **base de datos real**: modelos, migraciones, seeders y consultas Eloquent. La Parte A la hacemos juntos en clase; después avanzas por **niveles**: el nivel 1 es repaso aplicado (la meta de HOY), el nivel 2 es lo nuevo de la sesión (la meta de la semana: scopes + etiquetas) y el nivel 3 es terreno avanzado extra.

> Los seeders del curso (`CategoriaSeeder` y `PostSeeder`) llegaron a tu proyecto con el pull del inicio de la clase. Verifica: `ls database/seeders` debe listarlos. Asumen las columnas `titulo`, `contenido` y `categoria_id`; si nombras distinto las tuyas, adapta el seeder.

## Parte A · Modelos, migraciones y datos (guiada en clase)

1. Genera los dos modelos CON su migración (el orden importa: `categorias` debe existir antes de que `posts` la referencie):
   ```bash
   php artisan make:model Categoria -m
   ```
   ```bash
   php artisan make:model Post -m
   ```

2. Completa las migraciones (en `database/migrations/`, las dos más recientes):
   ```php
   // ..._create_categorias_table.php
   Schema::create('categorias', function (Blueprint $table) {
       $table->id();
       $table->string('nombre');
       $table->timestamps();
   });
   ```
   ```php
   // ..._create_posts_table.php
   Schema::create('posts', function (Blueprint $table) {
       $table->id();
       $table->string('titulo');
       $table->text('contenido');
       $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
       $table->timestamps();
   });
   ```

3. Completa los modelos:
   ```php
   // app/Models/Categoria.php
   class Categoria extends Model
   {
       protected $fillable = ['nombre'];

       public function posts()
       {
           return $this->hasMany(Post::class);
       }
   }
   ```
   ```php
   // app/Models/Post.php
   class Post extends Model
   {
       protected $fillable = ['titulo', 'contenido', 'categoria_id'];

       public function categoria()
       {
           return $this->belongsTo(Categoria::class);
       }
   }
   ```

4. Registra los seeders del curso en `database/seeders/DatabaseSeeder.php`. **Reemplaza el contenido completo de `run()`** (el `User::factory()` que trae de fábrica estorba: crea un usuario de prueba que truena si siembras dos veces). El orden importa:
   ```php
   public function run(): void
   {
       $this->call([
           CategoriaSeeder::class,
           PostSeeder::class,
       ]);
   }
   ```

5. Crea las tablas y siembra los datos:
   ```bash
   php artisan migrate --seed
   ```

   ✅ **Checkpoint A (todos deben llegar aquí):** en `php artisan tinker`:
   ```php
   Post::count();                    // => 8
   Post::first()->categoria->nombre; // => el nombre de su categoría
   Categoria::first()->posts;        // => colección de posts
   ```

## Nivel 1 (núcleo, la meta de todos) · La portada con datos reales

6. En `routes/web.php`, la ruta `/` deja de inventar datos y consulta la BD (borra la colección de objetos):
   ```php
   use App\Models\Post;

   Route::get('/', function () {
       $posts = Post::with('categoria')->latest()->get();

       return view('portada', ['posts' => $posts]);
   });
   ```
   (`with('categoria')` trae las categorías en la misma pasada; `latest()` ordena del más nuevo al más viejo.)

7. Actualiza `<x-tarjeta-post>`: la categoría ya no es texto, es una **relación**, y la fecha ahora es `created_at` (un objeto de fecha que se formatea):
   ```blade
   {{ $post->categoria->nombre }}
   ```
   ```blade
   {{ $post->created_at->format('d/m/Y') }}
   ```
   (Si aún no extraes el componente, haz los mismos dos cambios en el `<article>` del `@foreach` de la portada.)

   ✅ **Checkpoint nivel 1:** la portada muestra los 8 avisos del seeder, del más nuevo al más viejo. Prueba final en Tinker:
   ```php
   Post::create(['titulo' => 'Aviso de prueba', 'contenido' => 'Creado desde Tinker.', 'categoria_id' => 1]);
   ```
   Recarga la portada: tu aviso aparece hasta arriba. Eso es el blog leyendo la BD en vivo.

## Nivel 2 (intermedio) · Borradores y scopes

8. Agrega la columna `publicado`:
   ```bash
   php artisan make:migration add_publicado_to_posts_table
   ```
   ```php
   public function up(): void
   {
       Schema::table('posts', function (Blueprint $table) {
           $table->boolean('publicado')->default(true);
       });
   }

   public function down(): void
   {
       Schema::table('posts', function (Blueprint $table) {
           $table->dropColumn('publicado');
       });
   }
   ```
   ```bash
   php artisan migrate
   ```

9. En el modelo `Post`: suma `'publicado'` al `$fillable`, y agrega el cast y los **scopes** (consultas con nombre, reutilizables):
   ```php
   protected $casts = ['publicado' => 'boolean'];

   public function scopePublicados($query)
   {
       return $query->where('publicado', true);
   }

   public function scopeDeCategoria($query, $categoriaId)
   {
       return $query->where('categoria_id', $categoriaId);
   }
   ```

10. Manda dos avisos a borrador y prueba en Tinker:
    ```php
    Post::find(7)->update(['publicado' => false]);
    Post::find(8)->update(['publicado' => false]);
    Post::publicados()->count();                      // menor que Post::count()
    Post::publicados()->deCategoria(1)->latest()->get();
    ```

11. La portada solo debe mostrar lo publicado:
    ```php
    $posts = Post::publicados()->with('categoria')->latest()->get();
    ```

    ✅ **Checkpoint nivel 2:** los dos borradores desaparecen de la portada, pero `Post::count()` los sigue contando.

## El nivel 2 continúa · Etiquetas N:M

El nivel 2 se completa con las **etiquetas** (relación muchos a muchos con tabla pivote, `attach`/`sync`): sigue con `03-etiquetas.md`. Es parte de la meta de la semana, no un extra.

## Nivel 3 (avanzado, extra) · N+1, accessors y soft deletes

Si quieres ir más allá, sigue con `04-nivel3-avanzado.md`: mide el problema N+1 con el query log, crea el accessor `resumen` y agrega soft deletes al blog. Cuenta como extra en tu PR.

## Problemas comunes

| Síntoma | Causa probable |
|---|---|
| `SQLSTATE: no such table: categorias` al migrar | La migración de `posts` corrió antes que la de `categorias`; el orden lo marca el timestamp del nombre de archivo (por eso Categoria se crea primero) |
| `Class "Database\Seeders\PostSeeder" not found` | Los seeders del curso no están en tu proyecto: te faltó el pull del inicio de la clase |
| `table posts has no column named titulo` al sembrar | Tus columnas se llaman distinto; adapta `PostSeeder.php` a tus nombres |
| `UNIQUE constraint failed: users.email` al sembrar de nuevo | Dejaste el `User::factory()` que traía `DatabaseSeeder` de fábrica; no es re-ejecutable. Bórralo (paso 4) o usa `migrate:fresh --seed` |
| `Call to undefined method Post::publicados()` | En el modelo el método se llama `scopePublicados` (con prefijo `scope`); se invoca sin él |
| `Attempt to read property "nombre" on string` | La ruta sigue mandando los objetos de ejemplo; el componente ya espera la relación real |
| Todo enredado y quieres empezar de cero | `php artisan migrate:fresh --seed` borra TODAS las tablas y las recrea con seeders (solo en desarrollo, jamás con datos valiosos) |
