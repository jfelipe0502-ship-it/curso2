# blog-avisos · Curso 2

Tu proyecto del curso: un blog interno de avisos que crece cada sesión.

## Primer arranque (sesión 1)

1. Haz **Fork** de este repositorio (botón arriba a la derecha) y clona TU fork; después conecta este repo como `upstream` (la Guía de entorno del curso de Moodle lo lleva paso a paso):
   ```bash
   git clone https://github.com/TU-USUARIO/curso2.git blog-avisos
   cd blog-avisos
   git remote add upstream https://github.com/ramsessal/curso2.git
   ```
2. Abre la carpeta en VS Code y acepta **"Reopen in Container"** (guía de entorno en el curso de Moodle).
3. En la terminal del contenedor: `bash .devcontainer/crear-proyecto.sh` (o el `composer create-project` manual de la guía). Deja todo listo, incluido `npm install`.
4. Para trabajar, un solo comando: `composer run dev` (levanta Laravel y Vite juntos en una terminal; Ctrl+C detiene todo).

## Cómo entrego cada sesión

```bash
git checkout main
git pull upstream main   # trae la base de la sesión (desde la sesión 2)
git push
git checkout -b sesionN-tarea
# ... commits de tu trabajo ...
git push -u origin sesionN-tarea
```

Abre el **Pull Request** hacia el `main` de **TU fork** (cuidado: GitHub propone por defecto el repositorio del curso como base; cámbiala en el selector a tu usuario), llena la plantilla (checklist de la sesión) y **pega la URL del PR en la Tarea de Moodle** de esa sesión antes de la fecha límite (víspera de la siguiente clase, 23:59).

Consejo: abre el PR **en borrador desde la clase misma**; lo completas con calma antes de la fecha límite. La rúbrica completa está publicada en Moodle.

## ¿La página carga sin estilos?

1. Revisa que `composer run dev` (o al menos `npm run dev`) esté corriendo (el 90% de las veces es esto).
2. Este repo trae un `vite.config.js` propio que hace funcionar Vite en el contenedor y en Codespaces; si tu proyecto usa otro, tráete el de aquí.
3. **En Codespaces**: el puerto 5173 se hace público solo al arrancar (mensaje "[OK] Puerto 5173 publico"). Si no pasó: pestaña PORTS → clic derecho en **5173** → Port Visibility → **Public**.
4. **`composer run dev` dice "Port already in use"**: quedaron procesos huérfanos de una corrida anterior. Límpialos y vuelve a arrancar:
   ```bash
   pkill -f 'php -S'; pkill -f vite; pkill -f 'queue:listen'; composer run dev
   ```
