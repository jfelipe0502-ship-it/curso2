#!/usr/bin/env bash
# Crea el proyecto Laravel del curso EN esta carpeta, conservando
# .devcontainer/, .github/ y README.md que ya vienen en la plantilla.
#
# Es el equivalente transparente de:
#   composer create-project laravel/laravel blog-avisos
# con un paso extra: como esta carpeta no está vacía (trae la plantilla),
# el proyecto se crea en un directorio temporal y luego se copia aquí.
set -e

if [ -f artisan ]; then
    echo "Ya existe un proyecto Laravel aquí (encontré 'artisan'). No hago nada."
    exit 0
fi

TMP=/tmp/laravel-nuevo

echo ""
echo "=== Paso 1/3: composer create-project laravel/laravel (1 a 3 minutos) ==="
rm -rf "$TMP"
composer create-project laravel/laravel "$TMP" --no-interaction --prefer-dist

echo ""
echo "=== Paso 2/3: copiando el proyecto a esta carpeta ==="
# -n: no sobreescribe lo que ya existe (la plantilla se respeta)
cp -rn "$TMP"/. .
# vendor/ vive en un volumen: se instala aquí directo
if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction
fi

echo ""
echo "=== Paso 3/3: base de datos y clave de la aplicación ==="
[ -f .env ] || cp .env.example .env
grep -q "^APP_KEY=base64" .env || php artisan key:generate
touch database/database.sqlite
php artisan migrate --force

rm -rf "$TMP"

echo ""
echo "=== Listo. Tu proyecto Laravel está vivo. Siguientes pasos: ==="
echo "  php artisan serve --host=0.0.0.0     (terminal 1)"
echo "  npm install && npm run dev            (terminal 2)"
echo ""
