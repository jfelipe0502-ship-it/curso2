# Version PowerShell del helper, para quien trabaja con Laragon (sin contenedor).
# Hace lo mismo que crear-proyecto.sh: composer create-project en una carpeta
# temporal y copia aqui sin pisar la plantilla (.devcontainer, .github, README).
$ErrorActionPreference = "Stop"

if (Test-Path "artisan") {
    Write-Host "Ya existe un proyecto Laravel aqui (encontre 'artisan'). No hago nada." -ForegroundColor Yellow
    exit 0
}

$tmp = Join-Path $env:TEMP "laravel-nuevo"

Write-Host ""
Write-Host "=== Paso 1/3: composer create-project laravel/laravel (1 a 3 minutos) ===" -ForegroundColor Cyan
if (Test-Path $tmp) { Remove-Item -Recurse -Force $tmp }
composer create-project laravel/laravel $tmp --no-interaction --prefer-dist

Write-Host ""
Write-Host "=== Paso 2/3: copiando el proyecto a esta carpeta (sin pisar la plantilla) ===" -ForegroundColor Cyan
robocopy $tmp . /E /XC /XN /XO /NFL /NDL /NJH /NJS | Out-Null
if ($LASTEXITCODE -ge 8) { throw "robocopy fallo con codigo $LASTEXITCODE" }
if (-not (Test-Path "vendor\autoload.php")) { composer install --no-interaction }

Write-Host ""
Write-Host "=== Paso 3/3: base de datos y clave de la aplicacion ===" -ForegroundColor Cyan
if (-not (Test-Path ".env")) { Copy-Item ".env.example" ".env" }
$envTexto = Get-Content ".env" -Raw
if ($envTexto -notmatch "APP_KEY=base64") { php artisan key:generate }
if (-not (Test-Path "database\database.sqlite")) { New-Item -ItemType File "database\database.sqlite" | Out-Null }
php artisan migrate --force

Remove-Item -Recurse -Force $tmp

Write-Host ""
Write-Host "=== Listo. Tu proyecto Laravel esta vivo. Siguientes pasos: ===" -ForegroundColor Green
Write-Host "  php artisan serve     (terminal 1)"
Write-Host "  npm install; npm run dev   (terminal 2)"
