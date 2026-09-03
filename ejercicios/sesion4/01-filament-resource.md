# Ejercicio 1 · Tu primer Resource de Filament (en clase + ~45 min en la semana)

Filament es un constructor de paneles administrativos: le declaras qué modelo, qué campos y qué columnas, y él pinta la lista, el formulario de alta, el de edición, los filtros y los botones. Hoy el CRUD que escribiste a mano en las sesiones 2 y 3 lo genera un comando, y lo que escribiste es justo lo que te permite leer lo generado.

> Requisitos: el blog con los modelos `Post` y `Categoria` (si te falta algo, `bash .devcontainer/nivelar-blog.sh` lo crea) y las extensiones de PHP que Filament exige (`bash .devcontainer/preparar-filament.sh`, alrededor de un minuto la primera vez).

## Parte 0 · Instalar (en clase, 10 min)

```bash
composer require filament/filament:"^5.0"
php artisan filament:install --panels
composer run dev
```

`composer require` tarda de 2 a 5 minutos según la red. `filament:install --panels` crea el panel (`app/Providers/Filament/AdminPanelProvider.php`) y lo registra en `bootstrap/providers.php`. Abre `/admin`: te recibe una pantalla de login que no escribiste. Entra con `admin@blog.test` / `secreto123`.

> Si `composer require` falla mencionando `ext-intl` o `ext-zip`, no corrió `preparar-filament.sh`. Córrelo y repite el `require`.

## Parte 1 · El Resource, generado desde tu tabla (en clase, 10 min)

```bash
php artisan make:filament-resource Post --generate
```

El comando pregunta cuál columna es el título del registro: escribe `titulo`. `--generate` lee las columnas de tu tabla `posts` y escribe el formulario y la tabla por ti. Deja seis archivos y ninguna vista:

```
app/Filament/Resources/Posts/
├── PostResource.php          qué modelo, qué icono, qué páginas
├── Schemas/PostForm.php      los campos y su validación
├── Tables/PostsTable.php     columnas, filtros y acciones
└── Pages/
    ├── ListPosts.php         index
    ├── CreatePost.php        create + store
    └── EditPost.php          edit + update + destroy
```

Recarga `/admin`: apareció **Posts** en el menú. Crea un aviso, edítalo y bórralo. Luego abre tu portada: el aviso está ahí, porque es la misma tabla y el mismo modelo.

## Parte 2 · Ajustar el formulario (en clase, 5 min)

Abre `Schemas/PostForm.php`. Lo generado trae `categoria_id` como número y `user_id` como campo, porque Filament vio dos enteros en la tabla, no dos relaciones. Déjalo así:

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

->components([
    TextInput::make('titulo')
        ->required()
        ->maxLength(255),
    Select::make('categoria_id')
        ->label('Categoría')
        ->relationship('categoria', 'nombre')
        ->required(),
    Textarea::make('contenido')
        ->required()
        ->columnSpanFull(),
    Toggle::make('publicado')
        ->default(true),
])
```

- **Un componente por campo.** Cada uno pinta el control y sabe guardar en la columna que lleva su nombre.
- **La validación vive en el campo.** `required()` y `maxLength(255)` son las mismas reglas que pusiste en `$request->validate()`. El mensaje de error también sale solo.
- **`relationship('categoria', 'nombre')`** usa la relación de tu modelo para llenar el desplegable: muestra el nombre, guarda el id.

Y el dueño del aviso, que ya no viene en el formulario, se asigna al crear. En `Pages/CreatePost.php`:

```php
protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id'] = auth()->id();

    return $data;
}
```

Es el `$datos['user_id'] = auth()->id();` de tu `store()`, en el lugar equivalente.

## Parte 3 · Ajustar la tabla (en clase, 5 min)

En `Tables/PostsTable.php`:

```php
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

->columns([
    TextColumn::make('titulo')
        ->searchable()
        ->sortable(),
    TextColumn::make('categoria.nombre')
        ->label('Categoría'),
    IconColumn::make('publicado')
        ->boolean(),
    TextColumn::make('created_at')
        ->dateTime('d/m/Y')
        ->sortable(),
])
->recordActions([
    EditAction::make(),
    DeleteAction::make(),
])
```

- `searchable()` pone la caja de búsqueda y arma el `where ... like`; `sortable()` hace clicable el encabezado.
- `categoria.nombre`, con punto, cruza la relación. Es el `$post->categoria->nombre` de tu tarjeta, y Filament carga la relación con `with()` para no caer en el N+1 de la sesión 2.
- `recordActions` son los botones de cada fila. `DeleteAction` pide confirmación solo.

## Nivel 1 · Un campo nuevo de punta a punta (obligatorio)

1. Migración:
   ```bash
   php artisan make:migration add_resumen_to_posts_table
   ```
   ```php
   Schema::table('posts', function (Blueprint $table) {
       $table->string('resumen', 160)->nullable();
   });
   ```
   ```bash
   php artisan migrate
   ```
2. `'resumen'` en el `$fillable` de `Post`. Sin esto Filament lo muestra pero no lo guarda.
3. En el formulario: `TextInput::make('resumen')->maxLength(160)->columnSpanFull()`.
4. En la tabla: `TextColumn::make('resumen')->limit(40)`.

Prueba: crea un aviso con resumen desde el panel y míralo en la tabla.

## Nivel 2 · Filtro y acción (obligatorio, en la semana)

Filtros, en `->filters([...])` de la tabla:

```php
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

SelectFilter::make('categoria_id')
    ->label('Categoría')
    ->relationship('categoria', 'nombre')
    ->preload(),

TernaryFilter::make('publicado')
    ->label('¿Publicados?')
    ->trueLabel('Solo publicados')
    ->falseLabel('Solo borradores'),
```

Son tus scopes `deCategoria()` y `publicados()` de la sesión 2, pintados. Se combinan entre sí y con la búsqueda en una sola consulta.

La acción, al inicio de `->recordActions([...])`:

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('publicar')
    ->label('Publicar')
    ->icon('heroicon-o-check-circle')
    ->color('success')
    ->requiresConfirmation()
    ->visible(fn (Post $record) => ! $record->publicado)
    ->action(fn (Post $record) => $record->update(['publicado' => true])),
```

Un botón por fila que recibe el registro de esa fila y corre tu código: un método de controlador sin ruta ni vista. `visible()` decide fila por fila; `requiresConfirmation()` pone el modal.

Prueba: crea un borrador (publicado apagado) y publícalo desde su fila. El botón desaparece de esa fila.

## Nivel 3 · Opcional, cuenta como extra

**Opción A, un widget en el dashboard:**

```bash
php artisan make:filament-widget AvisosStats --stats-overview
```

En `app/Filament/Widgets/AvisosStats.php`:

```php
protected function getStats(): array
{
    return [
        Stat::make('Avisos', Post::count()),
        Stat::make('Borradores', Post::where('publicado', false)->count()),
    ];
}
```

**Opción B, si tu blog tiene etiquetas:** en el formulario, `CheckboxList::make('etiquetas')->relationship('etiquetas', 'nombre')`. Filament hace el `sync()` por ti al guardar.

## Momento de leer código real

Un Resource de producción se ve exactamente como el tuyo: la misma clase `Resource` con `form()` y `table()` delegando en `Schemas/` y `Tables/`, las mismas columnas con `searchable()` y `sortable()`, el mismo `TernaryFilter`, las mismas `EditAction` y `DeleteAction`. Los sistemas grandes tienen decenas de estos; cada uno se lee igual que el que acabas de escribir.
