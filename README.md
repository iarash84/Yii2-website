# Yii2 Website

An older company website based on the Yii 2 Advanced Project Template, updated
to run on PHP 8.2 and Yii 2.0.55.

## Requirements

- PHP 8.2 with `mbstring`, `openssl`, `pdo_mysql`, and `fileinfo`
- Composer 2
- MySQL or MariaDB

Enabling PHP's `zip` extension is strongly recommended because Composer uses it
to install distribution archives. With XAMPP, uncomment `extension=zip` in
`C:\xampp\php\php.ini` and restart the terminal/web server.

## Local setup

```powershell
composer install
php init --env=Development --overwrite=All
```

Create the database and load the included snapshot:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
cmd.exe /d /c "C:\xampp\mysql\bin\mysql.exe --default-character-set=utf8 -u root portal < db\portal.sql"
```

The development database defaults are in
`common/config/main-local.php`: database `portal`, user `root`, and an empty
password. Change that ignored local file if your MySQL credentials differ.

## Run

Run the frontend and backend in separate terminals:

```powershell
php yii serve --docroot=frontend/web 127.0.0.1:8080
php yii serve --docroot=backend/web 127.0.0.1:8081
```

- Frontend: <http://127.0.0.1:8080/>
- Backend: <http://127.0.0.1:8081/>

For Apache/XAMPP, point separate virtual-host document roots at `frontend/web`
and `backend/web`; do not expose the repository root.

## Checks

```powershell
php requirements.php
php yii help
composer validate --no-check-publish
composer audit --locked
```

The historical Codeception 2 test integration was removed because it does not
run on PHP 8.2. The old tests remain under `tests/` as migration material for a
future test-suite upgrade.
