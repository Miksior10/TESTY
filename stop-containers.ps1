# Skrypt PowerShell do zatrzymania kontenerów Docker

Write-Host "=== Zatrzymywanie kontenerów Docker ===" -ForegroundColor Cyan
Write-Host ""

# Sprawdź czy kontenery działają
$containers = docker ps --filter "name=mysql-db" --filter "name=php-app" --filter "name=pma" --format "{{.Names}}"

if (-not $containers) {
    Write-Host "Brak uruchomionych kontenerów." -ForegroundColor Yellow
    exit
}

Write-Host "Znalezione kontenery:" -ForegroundColor Green
docker ps --filter "name=mysql-db" --filter "name=php-app" --filter "name=pma" --format "table {{.Names}}\t{{.Status}}"
Write-Host ""

$response = Read-Host "Czy chcesz zatrzymać kontenery? (T/N)"
if ($response -eq "T" -or $response -eq "t" -or $response -eq "Y" -or $response -eq "y") {
    Write-Host "Zatrzymywanie kontenerów..." -ForegroundColor Yellow
    docker-compose stop
    Write-Host "Kontenery zostały zatrzymane." -ForegroundColor Green
} else {
    Write-Host "Anulowano." -ForegroundColor Gray
}

