@echo off
REM Skrypt batch do zatrzymania kontenerów Docker

echo === Zatrzymywanie kontenerow Docker ===
echo.

REM Sprawdź czy kontenery działają
docker ps --filter "name=mysql-db" --filter "name=php-app" --filter "name=pma" --format "{{.Names}}" | findstr /R "mysql-db php-app pma" >nul
if errorlevel 1 (
    echo Brak uruchomionych kontenerow.
    pause
    exit /b
)

echo Znalezione kontenery:
docker ps --filter "name=mysql-db" --filter "name=php-app" --filter "name=pma" --format "table {{.Names}}\t{{.Status}}"
echo.

set /p response="Czy chcesz zatrzymac kontenery? (T/N): "
if /i "%response%"=="T" (
    echo Zatrzymywanie kontenerow...
    docker-compose stop
    echo Kontenery zostaly zatrzymane.
) else if /i "%response%"=="Y" (
    echo Zatrzymywanie kontenerow...
    docker-compose stop
    echo Kontenery zostaly zatrzymane.
) else (
    echo Anulowano.
)

pause

