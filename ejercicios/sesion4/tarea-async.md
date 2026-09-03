# Tarea de la semana · Sesión 4 (~1.5h)

> Tu Pull Request de esta sesión **ya quedó abierto en clase** (o es el mismo de la sesión anterior). Esta semana solo le sumas commits: cada `git push` a la misma rama se agrega solo al PR.

## 1. Termina el ejercicio del Resource (~45 min)

- **Nivel 1 (obligatorio):** el campo `resumen` de punta a punta: migración, `TextInput` con `maxLength(160)` en el formulario y su columna en la tabla.
- **Nivel 2 (obligatorio):** el `SelectFilter` por categoría y la acción **Publicar** con confirmación, visible solo en los avisos que son borrador.
- **Nivel 3 (opcional, cuenta como extra):** un widget en el dashboard con el total de avisos y cuántos son borradores; o, si tu blog tiene etiquetas, un `CheckboxList` por relación en el formulario.

La guía está en tu proyecto: `ejercicios/sesion4/01-filament-resource.md`.

## 2. Quién entra al panel (~15 min)

En local, Filament deja entrar a cualquier usuario de la tabla. Implementa `FilamentUser` en `app/Models/User.php` con `canAccessPanel()` para que solo entren `admin` y `editor`. Está al final de `ejercicios/sesion4/02-policies-en-filament.md`. Prueba: crea un usuario con rol `visitante` en Tinker e intenta entrar con él.

## 3. Lo que haya quedado de la sesión 3 (~20 min)

Si tu blog público todavía no tiene login propio o la Policy completa (`update`, `delete`, `before`, y ahora `deleteAny`), esta semana es el momento. Escribe también `viewAny` y `view` aunque regresen `true`: en los sistemas reales ninguna Policy depende del "permitido por ausencia". Las guías siguen en `ejercicios/sesion3/`.

## 4. Una pregunta para traer respondida (~10 min)

En tu blog público, quita el `@can` de la tarjeta de aviso y entra como editor. El botón **Editar** ahora aparece también en avisos ajenos. Da clic en uno. Escribe en tu PR, en tres líneas: qué respondió la aplicación, **cuál capa** lo detuvo, y por qué el panel de Filament no necesitó ese `@can` para esconder el mismo botón.

## Checklist del Pull Request

Copia esto en la descripción de tu PR y marca lo que cumples:

```markdown
- [ ] Filament instalado: /admin abre y entro con admin@blog.test
- [ ] PostResource generado, con formulario y tabla ajustados en clase
- [ ] Campo resumen: migración + formulario + columna (nivel 1)
- [ ] Filtro por categoría y acción Publicar con confirmación (nivel 2)
- [ ] PostPolicy con deleteAny: el editor ya no puede borrar en masa y solo edita lo suyo
- [ ] canAccessPanel() en User: solo admin y editor entran al panel
- [ ] Respondida la pregunta del @can
- [ ] Extra: widget o CheckboxList de etiquetas (nivel 3)
```

## Recordatorio del flujo

```bash
git add .
git commit -m "Sesion 4: panel con Filament"
git push
```

Tu PR ya existe: estos commits se le suman solos. Cuando termines, márcalo como **Ready for review** y confirma que la URL esté pegada en la Tarea de Moodle de esta sesión. La rúbrica está publicada: se puntúa el logro, reintentar nunca resta y cuenta tu mejor versión. Cualquier traba: al canal del curso con captura del error.
