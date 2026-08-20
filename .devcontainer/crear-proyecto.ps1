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
Write-Host "=== Paso 1/4: composer create-project laravel/laravel (1 a 3 minutos) ===" -ForegroundColor Cyan
if (Test-Path $tmp) { Remove-Item -Recurse -Force $tmp }
# Fijamos Laravel 12: es la version de los sistemas que el curso prepara a mantener
composer create-project "laravel/laravel:^12.0" $tmp --no-interaction --prefer-dist

Write-Host ""
Write-Host "=== Paso 2/4: copiando el proyecto a esta carpeta (sin pisar la plantilla) ===" -ForegroundColor Cyan
robocopy $tmp . /E /XC /XN /XO /NFL /NDL /NJH /NJS | Out-Null
if ($LASTEXITCODE -ge 8) { throw "robocopy fallo con codigo $LASTEXITCODE" }
if (-not (Test-Path "vendor\autoload.php")) { composer install --no-interaction }

Write-Host ""
Write-Host "=== Paso 3/4: base de datos y clave de la aplicacion ===" -ForegroundColor Cyan
if (-not (Test-Path ".env")) { Copy-Item ".env.example" ".env" }
$envTexto = Get-Content ".env" -Raw
if ($envTexto -notmatch "APP_KEY=base64") { php artisan key:generate }
if (-not (Test-Path "database\database.sqlite")) { New-Item -ItemType File "database\database.sqlite" | Out-Null }
php artisan migrate --force

Write-Host ""
Write-Host "=== Paso 4/4: npm install (dependencias de Vite y Tailwind) ===" -ForegroundColor Cyan
npm install --no-audit --no-fund

Remove-Item -Recurse -Force $tmp

Write-Host ""
Write-Host "=== Listo. Tu proyecto Laravel esta vivo. Para trabajar: ===" -ForegroundColor Green
Write-Host "  composer run dev     (levanta Laravel y Vite juntos; Ctrl+C detiene todo)"
