# EduFlow API

API Laravel pour une ecole permettant de gerer:

- l'authentification JWT
- les cours et leur tarification
- les recommandations basees sur les interets
- la wishlist des etudiants
- les inscriptions apres paiement en ligne simule Stripe
- l'organisation automatique en groupes de 25 participants maximum
- les statistiques enseignant

## Architecture

Le projet suit une architecture simple et maintenable:

- `Controllers` pour exposer les endpoints REST
- `Services` pour la logique metier
- `Repositories` pour l'acces aux donnees
- `Middleware` pour la gestion des roles

## Endpoints principaux

- `POST /api/signup`
- `POST /api/login`
- `POST /api/forgot-password`
- `POST /api/reset-password`
- `GET /api/courses`
- `POST /api/courses`
- `GET /api/recommendations`
- `GET /api/wishlist`
- `POST /api/wishlist/{course}`
- `POST /api/courses/{course}/enroll`
- `GET /api/teacher/stats`
- `GET /api/teacher/groups`

## Paiement Stripe

Pour garder le projet fonctionnel sans dependance externe supplementaire, le paiement est simule via un `payment_method` envoye a l'endpoint d'inscription. La reference est stockee dans `payment_reference`.

Exemple:

```json
{
  "payment_method": "pm_card_visa"
}
```

## Groupes automatiques

- chaque inscription payee affecte automatiquement l'etudiant a un groupe
- un groupe est limite a `25` etudiants
- si le groupe courant est plein, un nouveau groupe est cree automatiquement

## Lancer le projet

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

## Tests

```bash
php artisan test
```

## Documentation

Une specification OpenAPI minimale est disponible dans `docs/openapi.yaml`.
