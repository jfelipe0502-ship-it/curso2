#!/usr/bin/env bash
# Solo aplica en GitHub Codespaces: hace PUBLICO el puerto de Vite (5173)
# para que el navegador pueda pedirle los estilos. Sin esto la pagina se ve
# sin CSS. Corre en CADA arranque (postStartCommand) porque GitHub regresa
# el puerto a Private cuando el codespace se reinicia.
# En el contenedor local no hace nada (no existe CODESPACE_NAME).

[ -z "${CODESPACE_NAME:-}" ] && exit 0

if ! command -v gh >/dev/null 2>&1; then
    echo "AVISO: no encontre gh; haz publico el puerto 5173 a mano (PORTS -> clic derecho -> Port Visibility -> Public)."
    exit 0
fi

for intento in 1 2 3 4 5; do
    if gh codespace ports visibility 5173:public -c "$CODESPACE_NAME" >/dev/null 2>&1; then
        echo "[OK] Puerto 5173 publico: los estilos de Vite van a cargar."
        exit 0
    fi
    sleep 5
done

echo "AVISO: no pude hacer publico el puerto 5173 automaticamente."
echo "Hazlo a mano: pestana PORTS -> clic derecho en 5173 -> Port Visibility -> Public."
exit 0
