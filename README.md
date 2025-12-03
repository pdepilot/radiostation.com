# Darling FM - Laravel Platform

A full-featured radio station management platform built with Laravel 12.x, MySQL, and modern web technologies.

---

## 🚀 Quick Start

### Prerequisites
- **PHP 8.2+** (XAMPP 8.2+ includes this)
- **Composer 2.x**
- **Node.js 20+** / npm 10+
- **MySQL 8** (bundled with XAMPP)
- **XAMPP** for local development

### Installation

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

---

## 🔐 Default Credentials

After seeding, use these credentials to log in:

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@darlingfm.ng` | `Password123!` |

**Note**: New user registrations automatically receive the `dj` role.

---

## 📁 Project Structure

```
DARLING FM/
├── app/
│   ├── Http/Controllers/
│   │   ├── Frontend/          # Public-facing controllers
│   │   ├── Admin/             # Admin dashboard controllers
│   │   └── Auth/               # Authentication controllers
│   ├── Models/                # Eloquent models
│   └── Services/              # Business logic services
├── database/
│   ├── migrations/            # Database schema migrations
│   ├── seeders/               # Database seeders
│   └── factories/             # Model factories for testing
├── public/
│   └── assets/                # Static assets (CSS, JS, images)
├── resources/
│   └── views/
│       ├── frontend/          # Public-facing Blade templates
│       ├── admin/             # Admin dashboard templates
│       └── layouts/           # Layout templates
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes (to be implemented)
└── tests/                     # PHPUnit tests
```

---

## 🗺️ Navigation Guide

### Frontend Routes

| Route | Description | Controller |
|-------|-------------|------------|
| `/` | Homepage | `HomeController@index` |
| `/live` | Live stream page | `LiveStreamController@index` |
| `/shows` | All shows listing | `ShowController@index` |
| `/shows/{slug}` | Show details | `ShowController@show` |
| `/djs` | DJs/OAPs listing | `DjController@index` |
| `/playlist` | Playlist page | `PlaylistController@index` |
| `/podcasts` | Podcasts listing | `PodcastController@index` |
| `/podcasts/{slug}` | Podcast episode | `PodcastController@show` |
| `/news` | News listing | `NewsController@index` |
| `/news/{slug}` | News article | `NewsController@show` |
| `/contact` | Contact page | `ContactController@index` |

### Admin Routes (Requires Authentication)

All admin routes are prefixed with `/admin` and require authentication:

| Route | Description | Controller |
|-------|-------------|------------|
| `/admin` | Admin dashboard | `DashboardController@index` |
| `/admin/shows` | Manage shows | `AdminShowController` |
| `/admin/djs` | Manage DJs | `AdminDjController` |
| `/admin/news` | Manage news | `AdminNewsController` |
| `/admin/podcasts` | Manage podcasts | `AdminPodcastController` |
| `/admin/playlist` | Manage playlist | `AdminPlaylistController` |
| `/admin/livestreams` | Manage live streams | `AdminLiveStreamController` |
| `/admin/audience` | Audience analytics | `AudienceController@index` |
| `/admin/advertising` | Advertising packages | `AdvertisingController` |
| `/admin/revenue` | Revenue tracking | `RevenueController` |
| `/admin/settings` | Site settings | `SettingsController` |

---

## 🧪 Testing

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

- **Full Page Testing**: Use browser DevTools, Laravel Dusk, or manual testing
- **API Testing**: Use Postman, Insomnia, or Laravel HTTP tests
- **Performance**: Use Lighthouse (Chrome DevTools) or PageSpeed Insights

**Note**: Postman is for API testing only. For full page testing, use a browser or Laravel Dusk.

---

## 🎨 Frontend Development

### CSS Structure

The project uses page-specific CSS files located in `public/assets/css/`:

- `index.css` - Base styles (loaded on all pages)
- `contact.css` - Contact page styles
- `djs.css` - DJs page styles
- `live-stream.css` - Live stream page styles
- `playlist.css` - Playlist page styles
- `podcast.css` - Podcast pages styles

Page-specific CSS is automatically loaded based on the route name.

### JavaScript

Main JavaScript file: `public/assets/js/index.js`

### Asset Management

- Static assets (CSS, JS, images) are in `public/assets/`
- Use Laravel's `asset()` helper: `{{ asset('assets/css/index.css') }}`
- For Vite-compiled assets, use `@vite(['resources/css/app.css'])`

---

## 🔧 Common Commands

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

## 📊 Database Models

### Core Models

- `User` - Users (admins, DJs)
- `Dj` - On-air personalities
- `Show` - Radio shows
- `LiveStream` - Live streaming sessions
- `NewsPost` - News articles
- `Podcast` - Podcast episodes
- `PlaylistTrack` - Playlist entries
- `ContactMessage` - Contact form submissions
- `SiteSetting` - Site configuration
- `AdvertisingPackage` - Advertising packages
- `RevenueRecord` - Revenue tracking
- `AudienceMetric` - Audience analytics

### Relationships

- `Show` belongs to `Dj`
- `LiveStream` belongs to `Dj` and `Show`
- All models use slugs for SEO-friendly URLs

---

## 🌐 XAMPP Configuration

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

## 🐛 Troubleshooting

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

## 📝 Development Workflow

1. **Create a feature branch**: `git checkout -b feature/your-feature-name`
2. **Make changes**: Edit code, add tests
3. **Test locally**: Run `php artisan test` and manual testing
4. **Commit changes**: Use descriptive commit messages
5. **Push to remote**: `git push origin feature/your-feature-name`
6. **Create pull request**: Merge to `freds-code` branch

---

## 📋 TODO & Tasks

See [TODO.md](./TODO.md) for detailed frontend and backend development tasks.

---

## 🔗 Useful Links

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Breeze](https://laravel.com/docs/breeze)
- [Blade Templates](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)

---

## 📄 License

This project is proprietary software for Darling FM.

---

**Last Updated**: 2024-11-28
