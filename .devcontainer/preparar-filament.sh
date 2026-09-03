#!/usr/bin/env bash
# Prepara PHP para Filament: instala las extensiones intl y zip, que Filament
# exige y que la imagen del contenedor no trae. Tarda alrededor de un minuto
# la primera vez; despues no hace nada. Corre solo en cada arranque del
# contenedor (postStartCommand) y tambien puedes lanzarlo a mano:
#   bash .devcontainer/preparar-filament.sh

faltan=""
for ext in intl zip; do
    php -m 2>/dev/null | grep -qi "^${ext}$" || faltan="$faltan $ext"
done

if [ -z "$faltan" ]; then
    echo "[OK] intl y zip ya estan instaladas."
    exit 0
fi

echo "Instalando extensiones de PHP:${faltan} (alrededor de un minuto)..."
export DEBIAN_FRONTEND=noninteractive
sudo apt-get update -qq >/dev/null 2>&1 || true
sudo apt-get install -y -qq libicu-dev libzip-dev >/dev/null 2>&1

# docker-php-ext-install compila el modulo, pero su activacion automatica
# falla bajo sudo (no ve PHP_INI_DIR y busca /conf.d). El .ini se escribe a mano.
INI_SCAN_DIR=$(php --ini | grep 'Scan for additional' | awk -F': ' '{print $2}' | tr -d ' ')
for ext in $faltan; do
    sudo docker-php-ext-install "$ext" >/dev/null 2>&1 || true
    if [ -n "$INI_SCAN_DIR" ]; then
        echo "extension=$ext" | sudo tee "$INI_SCAN_DIR/docker-php-ext-$ext.ini" >/dev/null
    fi
done

ok=1
for ext in intl zip; do
    if php -m 2>/dev/null | grep -qi "^${ext}$"; then
        echo "[OK] $ext instalada y activa."
    else
        echo "AVISO: no pude activar $ext."
        ok=0
    fi
done
[ "$ok" = 1 ] || { echo "Avisa en el canal del curso con una captura de este mensaje."; exit 1; }
