# Dostęp do bazy danych przez Docker

## Informacje o bazie danych

- **Nazwa kontenera**: `mysql-db`
- **Baza danych**: `moja_strona`
- **Użytkownik**: `root`
- **Hasło**: `password`
- **Port zewnętrzny**: `3307` (mapowany z wewnętrznego `3306`)

## Sposoby dostępu

### 1. Dostęp przez wiersz poleceń (MySQL CLI)

```bash
# Wejście do kontenera MySQL
docker exec -it mysql-db mysql -u root -ppassword moja_strona

# Lub bezpośrednio z zewnątrz (jeśli masz zainstalowany klient MySQL)
mysql -h 127.0.0.1 -P 3307 -u root -ppassword moja_strona
```

### 2. Dostęp przez phpMyAdmin (interfejs webowy)

1. Upewnij się, że kontenery są uruchomione:
   ```bash
   docker-compose up -d
   ```

2. Otwórz przeglądarkę i przejdź do:
   ```
   http://localhost:8080
   ```

3. Zaloguj się:
   - **Serwer**: `db` (lub `mysql-db`)
   - **Użytkownik**: `root`
   - **Hasło**: `password`

### 3. Dostęp przez zewnętrzny klient MySQL

Użyj następujących parametrów połączenia:
- **Host**: `127.0.0.1` lub `localhost`
- **Port**: `3307`
- **Baza danych**: `moja_strona`
- **Użytkownik**: `root`
- **Hasło**: `password`

### 4. Sprawdzenie statusu kontenerów

```bash
# Sprawdź czy kontenery działają
docker-compose ps

# Zobacz logi bazy danych
docker logs mysql-db

# Zobacz logi phpMyAdmin
docker logs pma
```

### 5. Zarządzanie kontenerami

```bash
# Przejdź do katalogu z docker-compose.yml
cd strona

# Uruchom kontenery (w tle)
docker-compose up -d

# Uruchom kontenery (z widocznymi logami)
docker-compose up

# Zatrzymaj kontenery (zachowuje dane)
docker-compose stop

# Zatrzymaj i usuń kontenery (zachowuje dane w wolumenach)
docker-compose down

# Zatrzymaj i usuń kontenery oraz wolumeny (UWAGA: usuwa dane!)
docker-compose down -v

# Zatrzymaj pojedynczy kontener
docker stop mysql-db
docker stop php-app
docker stop pma

# Zatrzymaj wszystkie kontenery Docker na systemie
docker stop $(docker ps -q)
```

## Przykładowe zapytania SQL

Po zalogowaniu do MySQL CLI możesz wykonać:

```sql
-- Pokaż wszystkie tabele
SHOW TABLES;

-- Pokaż użytkowników
SELECT * FROM users;

-- Pokaż projekty
SELECT * FROM projects;

-- Pokaż czas pracy
SELECT * FROM work_time;
```

