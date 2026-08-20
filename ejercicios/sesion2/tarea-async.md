# Tarea de la semana · Sesión 2 (~2.5h)

> **Fecha límite: víspera de la sesión 3, a las 23:59** (la fecha exacta se publica en el canal del curso).
> La tarea **incluye terminar lo que haya quedado pendiente de los Ejercicios 1 y 2 de la clase**; el tiempo de clase era para arrancar acompañado, no era el límite.

Tres partes. La entrega es **un Pull Request dentro de tu fork del proyecto**, en una rama `sesion2-tarea`. Es el primer PR del curso: al final hay una guía express paso a paso.

## 1. Termina tu blog con base de datos (~45 min)

Completa lo que no haya quedado en clase:

- **Del Ejercicio 1:** tarjetas vía componente `<x-tarjeta-post>` (nada de clases repetidas en la portada) y formulario de contacto con estados `focus:`.
- **Nivel 1 (obligatorio):** la portada consulta la BD real (`Post::with('categoria')->latest()`), el componente muestra `$post->categoria->nombre` y `$post->created_at->format('d/m/Y')`.
- **Nivel 2 (obligatorio, lo nuevo de la sesión):** columna `publicado` + scopes `publicados()` y `deCategoria()` con la portada mostrando solo lo publicado (Ejercicio 2), **y las etiquetas N:M** con `attach`/`sync` (Ejercicio 3).
- **Nivel 3 (opcional, cuenta como extra):** N+1 medido, accessor `resumen` y soft deletes (Ejercicio 4).

## 2. El CRUD de avisos con su controlador (~50 min, obligatorio)

El blog completa el ciclo: **crear, editar y borrar avisos con validación real**, y todo vive en un `PostController` (regla desde hoy: `routes/web.php` solo enruta, cero lógica ahí). La receta completa, paso a paso, está en `05-crud-avisos.md`: nace el controlador, la portada se muda a `index()`, y llegan `create`/`store`, `edit`/`update` y `destroy` con sus vistas, `@csrf`, `@error`, `old()` y route model binding. Con esto tu blog ya lee Y escribe; en la siguiente sesión le pondremos control de QUIÉN puede escribir.

## 3. Set de consultas en Tinker (~45 min)

Crea un archivo **`consultas-tinker.md`** en la raíz de tu proyecto (entra al PR). Por cada consulta pega: la consulta que escribiste y, como comentario, qué devolvió. Deben estar probadas en `php artisan tinker`, no inventadas.

**Bloque A (todos):**

1. Los 5 posts publicados más recientes, mostrando solo `titulo` y `created_at`.
2. Cuántos posts están publicados y cuántos en borrador.
3. Los posts de una categoría usando tu scope `deCategoria`, ordenados del más nuevo al más viejo.
4. Los posts publicados cuyo título contenga una palabra que tú elijas (pista: `where` con `like`).
5. Crea un scope nuevo `recientes($dias = 7)` (posts creados en los últimos N días) y pruébalo encadenado con los demás: `Post::publicados()->recientes(30)->get();` (los datos del seeder traen fechas escalonadas a propósito).

**Bloque B (todos; usa las etiquetas del nivel 2):**

6. Posts sin ninguna etiqueta (pista: `doesntHave`).
7. Las etiquetas ordenadas por cuántos posts las usan, de mayor a menor (pista: `withCount`).
8. Una consulta combinada inventada por ti que use al menos: un scope, una condición sobre una relación (`whereHas`) y `with()`. Acompáñala de UNA línea explicando qué pregunta responde.

> **Tu referencia permanente: [laravel.com/docs/12.x/eloquent](https://laravel.com/docs/12.x/eloquent).** Es el manual real de este bloque; en este curso se consulta la documentación oficial, no se memoriza.

## Checklist del Pull Request

Copia esto en la descripción de tu PR y marca lo que cumples:

```markdown
- [ ] composer run dev funciona y la portada carga con estilos
- [ ] Portada con <x-tarjeta-post> y formulario de contacto con focus (Ejercicio 1)
- [ ] php artisan migrate --seed deja la BD lista (modelos Post y Categoria + seeders)
- [ ] Portada con datos reales: relación categoria y created_at formateado (nivel 1)
- [ ] Columna publicado + scopes, la portada solo muestra publicados (nivel 2)
- [ ] Etiquetas N:M funcionando: attach/sync probados en Tinker (nivel 2)
- [ ] CRUD de avisos en PostController: crear, editar y borrar con validación (@error + old); web.php solo enruta (Ejercicio 5)
- [ ] consultas-tinker.md con los Bloques A y B probados
- [ ] Extra: N+1 medido, accessor resumen y soft deletes (nivel 3, opcional)
```

## Guía express: tu primer Pull Request

Tu fork ya está al día (lo actualizamos al inicio de la clase), así que cada entrega son tres pasos:

```bash
git checkout -b sesion2-tarea
git add .
git commit -m "Sesión 2: base de datos, portada real y scopes"
git push -u origin sesion2-tarea
```

En GitHub aparece el botón **"Compare & pull request"**. **Cuidado con la base del PR**: GitHub propone por defecto el repositorio del curso; en el selector de arriba cambia el repositorio base a **TU fork** (tu usuario, rama `main`). Ábrelo **en borrador (draft)** desde la clase misma con lo que lleves, y en la semana haz más commits a la misma rama (se agregan solos al PR). Cuando termines: llena el checklist, marca el PR como **"Ready for review"** y **pega su URL en la Tarea de Moodle de la sesión** (ahí corre la fecha límite).

La rúbrica del curso está publicada en Moodle: se puntúa el logro, reintentar nunca resta y cuenta tu mejor versión. Cualquier traba: al canal del curso con captura del error.
