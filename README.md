# Projet Laravel 10 – Stack Moderne (Breeze, Vite, Tailwind)

## Description

Ce projet est une base prête à l’emploi pour démarrer un développement Laravel 10 avec :
- **Laravel 10** (backend PHP, MVC, Eloquent ORM)
- **Blade** (moteur de templates)
- **Laravel Breeze** (authentification simple, scaffolding Blade)
- **Vite** (bundler moderne pour le front)
- **Tailwind CSS** (framework CSS utilitaire)
- **MySQL (XAMPP)** (base de données locale)

Idéal pour un projet de certification ou une application web moderne.

---

## Prérequis

- PHP 8.1 ou supérieur (ici PHP 8.2.12 via XAMPP)
- Composer
- Node.js & npm
- XAMPP (MySQL, Apache)

---

## Installation & Démarrage

1. **Cloner ou copier ce dossier dans `C:/xampp/htdocs/EC2`**
2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```
3. **Installer les dépendances front**
   ```bash
   npm install
   ```
4. **Configurer l’environnement**
   - Copier `.env.example` en `.env` si besoin
   - Adapter la section base de données dans `.env` :
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=laravel
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - Créer la base `laravel` via phpMyAdmin si besoin
5. **Générer la clé d’application**
   ```bash
   php artisan key:generate
   ```
6. **Lancer les migrations**
   ```bash
   php artisan migrate
   ```
7. **Compiler les assets front**
   ```bash
   npm run build
   # ou pour le développement (hot reload)
   npm run dev
   ```
8. **Démarrer le serveur local**
   ```bash
   php artisan serve
   ```
   Accéder à [http://localhost:8000](http://localhost:8000)

---

## Fonctionnalités incluses

- Authentification (inscription, connexion, mot de passe oublié)
- Structure MVC Laravel prête à l’emploi
- Vues Blade stylisées avec Tailwind CSS
- Compilation front moderne avec Vite

---

## Conseils sécurité & bonnes pratiques

- Utiliser Eloquent/QueryBuilder pour éviter les injections SQL
- Valider toutes les entrées utilisateurs côté contrôleur
- Garder `.env` hors du versionnement (déjà dans `.gitignore`)
- Penser à mettre à jour les dépendances régulièrement

---

## Liens utiles
- [Documentation Laravel](https://laravel.com/docs/10.x)
- [Laravel Breeze](https://laravel.com/docs/10.x/starter-kits#laravel-breeze)
- [Vite](https://vitejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/)

---

## Auteur / Certification

Projet initialisé pour une certification Laravel avec stack moderne.


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
