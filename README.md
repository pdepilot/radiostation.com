# Darling FM Laravel Platform

Laravel 12.x reimplementation of the Darling FM experience for studio, newsroom and backend operators.  
The goal is to turn the static prototype into a maintainable application that runs comfortably inside XAMPP on macOS.

## Requirements

- PHP 8.2+ with required extensions (ships with XAMPP 8.2)
- Composer 2.x
- Node 20+ / npm 10+
- MySQL 8 (bundled with XAMPP)

## Local Setup (XAMPP)

1. **Clone or copy** the repo into `/Applications/XAMPP/xamppfiles/htdocs/laravel-projects/DARLING FM`.
2. **Install PHP dependencies**
   ```bash
   composer install
   ```
3. **Install & build frontend assets**
   ```bash
   npm install
   npm run build   # npm run dev for hot reloads
   ```
4. **Environment**
   - Duplicate `.env.example` to `.env`.
   - Update DB credentials (typical XAMPP defaults):
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=darling_fm
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - Generate an app key: `php artisan key:generate`
5. **Database**
   ```bash
   php artisan migrate --seed
   ```
   The seeder provisions demo admins/djs, schedules, playlists, podcasts, audience metrics and revenue records.
6. **Serve**
   - Point Apache’s virtual host (or XAMPP’s “Alias”) to `public/`.
   - Or run `php artisan serve` for quick testing.

## Default Access

The seeder provisions an administrator:

| Role  | Email              | Password      |
|-------|--------------------|---------------|
| Admin | admin@darlingfm.ng | Password123!  |

All newly registered users are created as DJs (role `dj`) and can be promoted from the admin dashboard.

## Code Structure Highlights

- `app/Models` – Rich models for DJs, Shows, News, Podcasts, Playlists, Live Streams, Advertising Packages, Revenue Records, Site Settings, Audience Metrics & Contact Messages.
- `app/Http/Controllers/Frontend` – Controllers for visitor-facing pages (`home`, `live`, `shows`, `djs`, `playlist`, `podcasts`, `news`, `contact`).
- `app/Http/Controllers/Admin` – CRUD controllers powering the admin dashboard, playlist rotation, livestream controls, analytics, advertising and finance modules.
- `resources/views/frontend` – Blade templates inspired by the static prototype, wired to dynamic data.
- `resources/views/admin` – Breeze based admin templates (`x-app-layout`) with forms/tables for each module.
- `legacy_static_site/` – archived copy of the original HTML/CSS/JS for reference.
- `public/assets` – migrated media (CSS/JS/images/audio/video) reused by the Laravel views.

## Automated Tests

`php artisan test` runs the Breeze feature suite.  
Requests that hit frontend layouts emit benign warnings because Vite attempts to read assets during CLI rendering; the suite still exits with status `0`.

## XAMPP Tips

- Add a vhost similar to:
  ```
  <VirtualHost *:80>
      DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/laravel-projects/DARLING FM/public"
      ServerName darlingfm.test
      <Directory ".../DARLING FM/public">
          AllowOverride All
          Require all granted
      </Directory>
  </VirtualHost>
  ```
- Restart Apache after editing configs.
- Ensure `storage/` and `bootstrap/cache/` are writable (`chmod -R 775` if needed).

## Next Steps

- Hook Laravel Echo / Pusher for real-time listener counts and chat.
-- Integrate media encoding/workflow for live streaming endpoints.
- Harden admin auth with MFA + roles/permissions (Spatie).
- Build REST/GraphQL endpoints for mobile clients.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
