# Darling FM - Radio Station Management Platform

A full-featured radio station management platform for Darling FM 107.3 Owerri, Imo State. Built with Laravel 12.x, featuring live streaming, content management, music promotion, advertising, and comprehensive analytics.

---

## Table of Contents

- [Project Title & Description](#project-title--description)
- [Tech Stack](#tech-stack)
- [Key Features](#key-features)
- [Technical Architecture](#technical-architecture)
- [Installation & Setup](#installation--setup)
- [Default Credentials](#default-credentials)
- [Project Structure](#project-structure)
- [Navigation Guide](#navigation-guide)
- [Testing](#testing)
- [Common Commands](#common-commands)
- [XAMPP Configuration](#xampp-configuration)
- [Troubleshooting](#troubleshooting)
- [Development Workflow](#development-workflow)
- [Authors & Acknowledgments](#authors--acknowledgments)

---

## Project Title & Description

**Darling FM** is a comprehensive radio station management platform designed for Darling FM 107.3 in Owerri, Imo State, Nigeria. The platform provides end-to-end management of radio operations including live streaming, show scheduling, content publishing, music promotion, advertising management, revenue tracking, and audience analytics.

---

## Tech Stack

### Backend

- **PHP 8.2+** - Server-side language
- **Laravel 12.x** - PHP framework
- **MySQL 8** - Relational database
- **Filament 3.x** - Admin panel framework
- **Laravel Breeze** - Authentication scaffolding
- **Spatie Laravel Permission** - Role-based access control
- **Spatie Laravel Media Library** - Media management
- **Laravel Socialite** - OAuth authentication
- **PragmaRX Google2FA** - Two-factor authentication
- **Paystack** - Payment gateway integration

### Frontend

- **Blade Templates** - Server-side templating
- **Tailwind CSS 3.x** - Utility-first CSS framework
- **Alpine.js 3.x** - Lightweight JavaScript framework
- **Vite 7.x** - Build tool and dev server
- **Axios** - HTTP client

### Development Tools

- **Composer 2.x** - PHP dependency manager
- **Node.js 20+ / npm 10+** - JavaScript runtime and package manager
- **XAMPP** - Local development environment
- **PHPUnit** - Testing framework
- **Laravel Pint** - Code style fixer

---

## Key Features

### Content Management

- News articles with SEO-friendly slugs
- Radio shows scheduling and management
- DJ/OAP profiles and bios
- Event management and promotion
- Podcast episodes
- Playlist tracking

### Live Streaming

- Real-time live stream integration
- Listener count tracking
- Active stream status monitoring
- Stream metadata management

### Music Promotion System

- Paid music promotion slots (limited availability)
- Automated slot management
- Paystack payment integration
- Promotion expiration via scheduler
- Waitlist system for full slots
- Click and view tracking

### Advertising & Revenue

- Advertising package management
- Revenue record tracking
- Advert view/click analytics
- Automated ad rotation

### Analytics & Reporting

- Audience metrics tracking
- Listener analytics (daily, weekly, monthly, yearly)
- Traffic analytics
- Content view statistics
- Revenue reporting

### Admin Panel

- Filament-based admin interface
- User management with roles (admin, user)
- Content moderation
- Site settings management
- Contact message handling
- Chatbot knowledge base management

### User Features

- User registration and authentication
- Social login (Google, Facebook)
- Two-factor authentication (2FA)
- Profile management
- Contact form submissions
- Search functionality

---

## Technical Architecture

### Application Structure

- **MVC Pattern**: Controllers handle requests, Models manage data, Views render output
- **Service Layer**: Business logic separated into service classes
- **Repository Pattern**: Data access abstraction
- **Observer Pattern**: Model events for automated actions
- **Middleware**: Authentication, authorization, and request filtering

### Database Architecture

- **Eloquent ORM**: Active record pattern for database interactions
- **Migrations**: Version-controlled database schema
- **Seeders**: Initial data population
- **Factories**: Test data generation
- **Relationships**: One-to-many, many-to-many associations

### Security

- **Role-Based Access Control (RBAC)**: Spatie Laravel Permission
- **CSRF Protection**: Laravel built-in
- **Password Hashing**: Bcrypt
- **Two-Factor Authentication**: Google2FA
- **OAuth Integration**: Laravel Socialite

### Automation

- **Laravel Scheduler**: Cron-based task automation
    - Auto-expire music promotions
    - Reset listener counts (weekly, monthly, yearly)
    - Update live shows status
- **Queue System**: Background job processing
- **Event System**: Decoupled event handling

### API Architecture

- RESTful API endpoints for frontend consumption
- Real-time polling endpoints
- Webhook handlers for payment processing
- JSON responses for AJAX requests

---

## Installation & Setup

### Prerequisites

- PHP 8.2+ (XAMPP 8.2+ includes this)
- Composer 2.x
- Node.js 20+ / npm 10+
- MySQL 8 (bundled with XAMPP)
- XAMPP for local development

### Installation Steps

```bash
# 1. Navigate to project directory
cd "/Applications/XAMPP/xamppfiles/htdocs/laravel-projects/DARLING FM"

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=darling_fm
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Create database (via phpMyAdmin or MySQL CLI)
# CREATE DATABASE darling_fm;

# 8. Run migrations and seeders
php artisan migrate --seed

# 9. Build frontend assets
npm run build
# OR for development with hot reload:
# npm run dev

# 10. Start development server
php artisan serve
# Visit http://127.0.0.1:8000
```

### Post-Installation

- Set up storage symlink: `php artisan storage:link`
- Configure file permissions: `chmod -R 775 storage bootstrap/cache`
- Set up scheduled tasks: Add Laravel scheduler to crontab

---

## Project Structure

```
DARLING FM/
├── app/
│   ├── Console/Commands/        # Scheduled commands
│   ├── Events/                   # Event classes
│   ├── Filament/                 # Filament admin resources
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Frontend/         # Public-facing controllers
│   │   │   ├── Admin/            # Admin dashboard controllers
│   │   │   └── Auth/             # Authentication controllers
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/            # Form request validation
│   ├── Models/                   # Eloquent models
│   ├── Observers/                # Model observers
│   ├── Providers/                # Service providers
│   └── Services/                 # Business logic services
├── database/
│   ├── migrations/               # Database schema migrations
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories for testing
├── public/
│   └── assets/                   # Static assets (CSS, JS, images)
├── resources/
│   └── views/
│       ├── frontend/             # Public-facing Blade templates
│       ├── admin/                # Admin dashboard templates
│       └── layouts/              # Layout templates
├── routes/
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
└── tests/                        # PHPUnit tests
```

---

## Navigation Guide

### Frontend Routes

| Route              | Description       | Controller                   |
| ------------------ | ----------------- | ---------------------------- |
| `/`                | Homepage          | `HomeController@index`       |
| `/live`            | Live stream page  | `LiveStreamController@index` |
| `/shows`           | All shows listing | `ShowController@index`       |
| `/shows/{slug}`    | Show details      | `ShowController@show`        |
| `/djs/{slug}`      | DJ/OAP profile    | `DjController@show`          |
| `/news`            | News listing      | `NewsController@index`       |
| `/news/{slug}`     | News article      | `NewsController@show`        |
| `/events`          | Events listing    | `EventController@index`      |
| `/contact`         | Contact page      | `ContactController@index`    |
| `/music-promotion` | Music promotion   | `MusicPromotionController`   |
| `/privacy`         | Privacy policy    | `PolicyController@privacy`   |
| `/terms`           | Terms of service  | `PolicyController@terms`     |
| `/faq`             | FAQ page          | `PolicyController@faq`       |

### Admin Routes (Filament Panel)

Access via `/admin` - requires authentication:

- Dashboard
- Shows Management
- DJs Management
- News Posts
- Events
- Live Streams
- Music Promotions
- Contact Messages
- Site Settings
- Site Analytics
- Users Management
- Chatbot Knowledge Base

---

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage
```

### Testing Tools

- **Full Page Testing**: Browser DevTools, Laravel Dusk, or manual testing
- **API Testing**: Postman, Insomnia, or Laravel HTTP tests
- **Performance**: Lighthouse (Chrome DevTools) or PageSpeed Insights

---

## Common Commands

```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Refresh database (drop all tables and re-run migrations)
php artisan migrate:fresh --seed

# Create a new migration
php artisan make:migration create_table_name

# Create a new model
php artisan make:model ModelName

# Create a new controller
php artisan make:controller ControllerName

# List all routes
php artisan route:list

# Start queue worker (if using queues)
php artisan queue:work
```

---

## XAMPP Configuration

### Virtual Host Setup

Add to `/Applications/XAMPP/xamppfiles/etc/httpd.conf` or create a vhost:

```apache
<VirtualHost *:80>
    DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/laravel-projects/DARLING FM/public"
    ServerName darlingfm.test
    <Directory "/Applications/XAMPP/xamppfiles/htdocs/laravel-projects/DARLING FM/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `/etc/hosts`:

```
127.0.0.1 darlingfm.test
```

Restart Apache after configuration changes.

### Permissions

Ensure Laravel can write to storage and cache:

```bash
chmod -R 775 storage bootstrap/cache
```

---

## Troubleshooting

### Assets Not Loading

- Ensure `public/storage` symlink exists: `php artisan storage:link`
- Check file permissions on `public/assets/`
- Clear browser cache

### Database Connection Issues

- Verify MySQL is running in XAMPP
- Check `.env` database credentials
- Ensure database `darling_fm` exists

### Route Not Found

- Clear route cache: `php artisan route:clear`
- Check route exists: `php artisan route:list`

### 500 Errors

- Check `storage/logs/laravel.log` for errors
- Ensure `.env` has `APP_DEBUG=true` for development
- Verify file permissions on `storage/` and `bootstrap/cache/`

---

## Development Workflow

1. **Create a feature branch**: `git checkout -b feature/your-feature-name`
2. **Make changes**: Edit code, add tests
3. **Test locally**: Run `php artisan test` and manual testing
4. **Commit changes**: Use descriptive commit messages
5. **Push to remote**: `git push origin feature/your-feature-name`
6. **Create pull request**: Merge to `development` branch

---

## Authors & Acknowledgments

**Written by**: David Fred (Team Lead, Backend Developer)

**Backend Contributor**: Victory Obadiah

**Frontend Developer**: Princewill Chijioke

---

## License

This project is proprietary software for Darling FM.

---

## Useful Links

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Laravel Breeze](https://laravel.com/docs/breeze)
- [Blade Templates](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
