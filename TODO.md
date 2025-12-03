# Darling FM Development TODO

This document outlines tasks for frontend and backend developers working on the Darling FM Laravel platform.

---

## 🎨 Frontend Developer Tasks

### UI/UX Enhancement
- [ ] **Responsive Design Audit**
  - Test all pages on mobile (320px+), tablet (768px+), and desktop (1024px+)
  - Fix any layout breaks or overflow issues
  - Ensure touch targets are at least 44x44px on mobile
  - Location: All `resources/views/frontend/*.blade.php` files

- [ ] **Component Refactoring**
  - Extract reusable components (show cards, post cards, DJ profiles)
  - Create Blade components in `resources/views/components/`
  - Replace duplicate markup with `@component` or `<x-component>` directives
  - Priority: High - reduces code duplication

- [ ] **CSS Organization**
  - Review and optimize `public/assets/css/*.css` files
  - Remove unused styles
  - Ensure consistent naming conventions (BEM or similar)
  - Add CSS comments for major sections
  - Consider splitting large files into modules

- [ ] **JavaScript Interactions**
  - Enhance mobile menu functionality in `public/assets/js/index.js`
  - Add smooth scroll behavior for anchor links
  - Implement lazy loading for images
  - Add loading states for async actions (contact form, etc.)
  - Test all interactive elements

- [ ] **Accessibility (A11y)**
  - Add ARIA labels to interactive elements
  - Ensure keyboard navigation works on all pages
  - Test with screen readers (VoiceOver/NVDA)
  - Add skip-to-content links
  - Ensure color contrast meets WCAG AA standards
  - Add alt text to all images

- [ ] **SEO Optimization**
  - Add meta descriptions to all pages (use `@section('meta')` in layout)
  - Implement Open Graph tags for social sharing
  - Add structured data (JSON-LD) for shows, DJs, podcasts
  - Create XML sitemap (Laravel package or manual)
  - Optimize image alt attributes

- [ ] **Performance**
  - Optimize images (compress, use WebP where possible)
  - Implement image lazy loading
  - Minify CSS/JS for production
  - Add browser caching headers
  - Test page load times (target: <3s on 3G)

- [ ] **Cross-browser Testing**
  - Test on Chrome, Firefox, Safari, Edge
  - Fix any browser-specific issues
  - Test on iOS Safari and Chrome Mobile

- [ ] **Form Validation & UX**
  - Add client-side validation to contact form
  - Show inline error messages
  - Add success/error toast notifications
  - Disable submit button during submission

- [ ] **Live Stream Player**
  - Integrate HTML5 audio player for live stream
  - Add play/pause controls
  - Show current listener count (if API available)
  - Add volume control

---

## ⚙️ Backend Developer Tasks

### API Development
- [ ] **REST API Endpoints**
  - Create API routes in `routes/api.php`
  - Implement API controllers in `app/Http/Controllers/Api/`
  - Add API authentication (Sanctum tokens)
  - Document endpoints (Postman collection or OpenAPI/Swagger)
  - Endpoints needed:
    - `GET /api/shows` - List all shows
    - `GET /api/shows/{id}` - Show details
    - `GET /api/djs` - List all DJs
    - `GET /api/live-stream` - Current live stream status
    - `GET /api/playlist` - Current playlist
    - `GET /api/news` - Latest news
    - `GET /api/podcasts` - Podcast episodes

- [ ] **Real-time Features**
  - Integrate Laravel Echo + Pusher/Broadcasting
  - Broadcast live listener count updates
  - Implement live chat functionality (if required)
  - Real-time playlist updates
  - Location: Create events in `app/Events/`, listeners in `app/Listeners/`

### Streaming Integration
- [ ] **Stream Status Management**
  - Create service to check stream health
  - Auto-update listener count from streaming server
  - Handle stream start/stop events
  - Location: `app/Services/StreamService.php`

- [ ] **Stream Analytics**
  - Track peak listening times
  - Store historical listener data
  - Generate reports for management
  - Location: Extend `AudienceMetric` model

### Email & Notifications
- [ ] **Email System**
  - Configure mail driver (SMTP/SendGrid/Mailgun)
  - Create email templates for:
    - Contact form submissions
    - Newsletter signups (if needed)
    - Admin notifications
  - Location: `resources/views/emails/`, `app/Mail/`

- [ ] **Notification System**
  - Set up Laravel Notifications
  - Notify admins of new contact messages
  - Notify DJs of show reminders
  - Location: `app/Notifications/`

### File Uploads & Media
- [ ] **Media Management**
  - Implement file upload for:
    - DJ avatars
    - Show hero images
    - Podcast covers
    - News images
  - Add image validation and resizing
  - Store files in `storage/app/public/` (symlink to `public/storage`)
  - Location: `app/Http/Requests/` for validation, controllers for handling

- [ ] **Audio/Video Handling**
  - Add podcast audio upload
  - Implement audio file validation
  - Generate thumbnails for videos (if needed)
  - Location: Use Laravel Media Library or custom implementation

### Security & Validation
- [ ] **Form Validation**
  - Review all form requests in `app/Http/Requests/`
  - Add rate limiting to contact form
  - Implement CSRF protection (already in place, verify)
  - Sanitize user inputs

- [ ] **Authentication & Authorization**
  - Implement role-based permissions (Spatie Permission package)
  - Add MFA for admin accounts (optional but recommended)
  - Audit admin actions (log who did what)
  - Location: `app/Policies/` for authorization

- [ ] **API Security**
  - Add rate limiting to API routes
  - Implement API versioning
  - Add request throttling

### Database & Performance
- [ ] **Database Optimization**
  - Add indexes to frequently queried columns
  - Review N+1 query issues (use eager loading)
  - Add database query logging in development
  - Optimize slow queries

- [ ] **Caching**
  - Cache site settings (use `Cache::remember()`)
  - Cache frequently accessed data (shows, DJs)
  - Implement cache tags for easy invalidation
  - Location: Add caching in controllers or use model events

### Admin Dashboard Enhancements
- [ ] **Dashboard Analytics**
  - Add charts/graphs for:
    - Listener trends
    - Revenue over time
    - Most popular shows
    - Top DJs by engagement
  - Use Chart.js or similar library
  - Location: `resources/views/admin/dashboard.blade.php`

- [ ] **Bulk Operations**
  - Add bulk delete for news posts, podcasts
  - Bulk status updates
  - Export data to CSV/Excel
  - Location: Admin controllers

- [ ] **Search & Filtering**
  - Add search functionality to admin lists
  - Implement filters (date range, status, etc.)
  - Add sorting options
  - Location: Admin controllers, add query scopes to models

### Testing
- [ ] **Unit Tests**
  - Write tests for models (factories already exist)
  - Test form validation
  - Test business logic
  - Location: `tests/Unit/`

- [ ] **Feature Tests**
  - Test all frontend routes
  - Test admin CRUD operations
  - Test authentication flows
  - Test API endpoints
  - Location: `tests/Feature/`

- [ ] **Integration Tests**
  - Test email sending
  - Test file uploads
  - Test payment processing (if applicable)

---

## 🔄 Shared Tasks

### Documentation
- [ ] **API Documentation**
  - Document all API endpoints
  - Create Postman collection
  - Add request/response examples

- [ ] **Code Documentation**
  - Add PHPDoc comments to all methods
  - Document complex business logic
  - Update README with new features

### Deployment Preparation
- [ ] **Environment Configuration**
  - Set up `.env.production` template
  - Document all required environment variables
  - Configure production database

- [ ] **Asset Compilation**
  - Set up production build process
  - Minify CSS/JS
  - Optimize images
  - Test production build locally

- [ ] **Error Handling**
  - Set up error logging (Sentry or similar)
  - Create custom error pages (404, 500, etc.)
  - Add user-friendly error messages

---

## 📋 Testing Tools

### For Full Page Testing
- **Browser DevTools**: Chrome/Firefox DevTools for responsive testing
- **Laravel Dusk**: Browser automation for E2E testing
- **Manual Testing**: Test all pages on different devices

### For API Testing
- **Postman**: Create collections for API endpoints
- **Insomnia**: Alternative to Postman
- **Laravel HTTP Tests**: Use `$this->get()`, `$this->post()` in feature tests

### For Performance Testing
- **Lighthouse**: Chrome DevTools for performance audits
- **PageSpeed Insights**: Google's tool for performance analysis

---

## 🚀 Next Steps After Development

1. **Code Review**: Review all changes before merging to main branch
2. **Staging Deployment**: Deploy to staging environment for client review
3. **User Acceptance Testing (UAT)**: Get feedback from stakeholders
4. **Production Deployment**: Deploy to production server
5. **Monitoring**: Set up error tracking and performance monitoring
6. **Backup Strategy**: Implement automated database backups
7. **SSL Certificate**: Ensure HTTPS is configured
8. **CDN Setup**: Consider CDN for static assets (CSS, JS, images)

---

## 📝 Notes

- All tasks should be completed on the `freds-code` branch
- Create feature branches for each major task
- Write tests alongside code (TDD approach recommended)
- Follow PSR-12 coding standards
- Use meaningful commit messages
- Update this TODO as tasks are completed

---

**Last Updated**: {{ date('Y-m-d') }}

