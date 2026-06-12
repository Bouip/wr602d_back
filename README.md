# Vroum Vroum — Back-end

API REST développée avec Symfony 8 et API Platform dans le cadre du module WR602D.

## Prérequis

- PHP 8.4
- Composer
- MySQL 8
- Symfony CLI

## Installation

### 1. Cloner le repo et installer les dépendances

```bash
composer install
```

### 2. Configurer l'environnement

```bash
cp .env .env.local
```

Modifier `.env.local` :

```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/wr602d?serverVersion=8.0.37"
MAILER_SERVICE_URL=http://localhost:8001
MAILER_API_KEY_NAME=X-API-KEY
MAILER_API_KEY_VALUE=supersecretkey
```

### 3. Créer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 4. Générer les clés JWT

```bash
php bin/console lexik:jwt:generate-keypair
```

### 5. Créer un administrateur

```bash
php bin/console app:create-admin
```

### 6. Lancer le serveur

```bash
symfony server:start
```

L'API est accessible sur `http://localhost:8000/api`
Le dashboard admin est accessible sur `http://localhost:8000/admin`

## Points d'API

| Méthode | Route | Description | Auth |
|---------|-------|-------------|------|
| POST | `/api/user/register` | Inscription | Non |
| POST | `/api/login_check` | Connexion (JWT) | Non |
| POST | `/api/scores/add` | Ajouter un score | JWT |
| GET | `/api/scores` | Récupérer ses scores | JWT |

## Tests

```bash
php bin/phpunit tests/Api/ScoreApiTest.php
```

## Commandes

```bash
# Créer un administrateur
php bin/console app:create-admin
```

## Stack technique

- **Symfony 8** — framework PHP
- **API Platform** — exposition REST
- **Lexik JWT** — authentification
- **Doctrine** — ORM
- **MySQL** — base de données