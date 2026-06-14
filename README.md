# VROUM VROUM — Back

API REST faite avec Symfony 8 et API Platform.

## Prérequis

- PHP 8.4, Composer, MySQL 8, Symfony CLI

## Installation

```bash
composer install
cp .env .env.local
```

Modifie `.env.local` :

```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/wr602d?serverVersion=8.0.37"
MAILER_SERVICE_URL=http://localhost:8001
MAILER_API_KEY_NAME=X-API-KEY
MAILER_API_KEY_VALUE=supersecretkey
```

Puis :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console lexik:jwt:generate-keypair
php bin/console app:create-admin
symfony server:start
```

API dispo sur `http://localhost:8000/api`
Admin dispo sur `http://localhost:8000/admin`

## Points d'API principaux

| Méthode | Route | Auth |
|---------|-------|------|
| POST | `/api/user/register` | Non |
| POST | `/api/login_check` | Non |
| POST | `/api/scores/add` | JWT |
| GET | `/api/scores` | JWT |

## Tests

```bash
php bin/phpunit tests/Api/ScoreApiTest.php
```

## Stack

- Symfony 8, API Platform, Lexik JWT, Doctrine, MySQL