# Skrypt PowerShell do dostępu do bazy danych MySQL w Dockerze

Write-Host "=== Dostęp do bazy danych MySQL ===" -ForegroundColor Cyan
Write-Host ""

# Sprawdź czy kontener działa
$containerRunning = docker ps --filter "name=mysql-db" --format "{{.Names}}"

if (-not $containerRunning) {
    Write-Host "Kontener mysql-db nie jest uruchomiony!" -ForegroundColor Red
    Write-Host "Uruchamianie kontenerów..." -ForegroundColor Yellow
    docker-compose up -d
    Start-Sleep -Seconds 3
}

Write-Host "Łączenie z bazą danych..." -ForegroundColor Green
Write-Host "Baza: moja_strona | Użytkownik: root" -ForegroundColor Gray
Write-Host ""

# Wejście do MySQL CLI
docker exec -it mysql-db mysql -u root -ppassword moja_strona

