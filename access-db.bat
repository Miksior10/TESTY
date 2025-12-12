@echo off
REM Skrypt batch do dostępu do bazy danych MySQL w Dockerze

echo === Dostęp do bazy danych MySQL ===
echo.

REM Sprawdź czy kontener działa
docker ps --filter "name=mysql-db" --format "{{.Names}}" | findstr /C:"mysql-db" >nul
if errorlevel 1 (
    echo Kontener mysql-db nie jest uruchomiony!
    echo Uruchamianie kontenerow...
    docker-compose up -d
    timeout /t 3 /nobreak >nul
)

echo Laczenie z baza danych...
echo Baza: moja_strona ^| Uzytkownik: root
echo.

REM Wejście do MySQL CLI
docker exec -it mysql-db mysql -u root -ppassword moja_strona

