# Talibon Polytechnic College — Official Website

The official website of **Talibon Polytechnic College (TPC)**, built with Laravel. It serves as the public-facing platform for the college, providing information on academics, programs, admissions, news, services, and organizational structure — alongside a full-featured admin panel for content management.

---

## Tech Stack

- **Framework:** Laravel 11 (PHP)
- **Frontend:** Blade templates, Tailwind CSS, Vite
- **Database:** SQLite (development) / configurable via `.env`
- **Storage:** Laravel filesystem (public disk)
- **Mail:** Configurable via Laravel Mail (contact form notifications, password reset)

---

## Features

### Public Site

- **Home** — Landing page with dynamic about slides and highlights
- **Academics** — Overview of academic offerings
- **Programs** — Detailed program pages with descriptions, people, achievements, and gallery
- **Admissions** — Admission requirements organized by section and item
- **Services** — College services with rich content and social links
- **News** — News feed with individual post pages and likes
- **Org Chart** — Interactive organizational chart with tree node rendering
- **Contact** — Contact form with email notification to administrators

### Admin Panel

- **Dashboard** — Overview of site activity
- **News Posts** — Create, edit, review, and publish/unpublish news articles
- **Programs** — Manage programs, program details, people, and achievements
- **Services** — Manage services and their content sections
- **Admissions** — Manage admission sections and items
- **About Slides** — Manage homepage carousel slides
- **Org Chart** — Manage organizational chart nodes
- **Messages** — View and manage contact form submissions
- **Settings** — Manage site-wide settings
- **Users** — Admin user management (Super Admin only)

### Authentication & Roles

- Session-based authentication (Laravel Breeze)
- Two roles: **Admin** and **Super Admin** (via middleware)
- Email verification and password reset support

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Auth/           # Authentication controllers
│   │   └── *.php           # Public-facing controllers
│   └── Middleware/
│       ├── AdminMiddleware.php
│       └── SuperAdminMiddleware.php
├── Models/                 # Eloquent models
├── Mail/                   # Mailable classes
└── Support/
    └── Settings.php        # Site settings helper

resources/views/
├── admin/                  # Admin panel views
├── public/                 # Public site views
├── layouts/                # Shared layouts
└── partials/               # Reusable nav and footer

database/
├── migrations/             # All database migrations
└── seeders/                # Seeders for initial data
```

---

## Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- SQLite (or another supported database)

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd tpc_website

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Run database migrations and seeders
php artisan migrate --seed

# 6. Link storage for public uploads
php artisan storage:link

# 7. Build frontend assets
npm run build

# 8. Start the development server
php artisan serve
```

The site will be available at `http://localhost:8000`.

---

## Seeded Data

The following seeders are included for development:

| Seeder              | Description                             |
| ------------------- | --------------------------------------- |
| `SuperAdminSeeder`  | Creates the default super admin account |
| `SiteSettingSeeder` | Populates default site settings         |
| `ProgramSeeder`     | Sample academic programs                |
| `NewsPostSeeder`    | Sample news articles                    |
| `AdmissionSeeder`   | Sample admission sections and items     |
| `OrgChartSeeder`    | Sample organizational chart structure   |

> **Note:** Check `SuperAdminSeeder.php` for the default super admin credentials and change them after first login.

---

## Environment Variables

Key variables to configure in your `.env` file:

```env
APP_NAME="Talibon Polytechnic College"
APP_URL=http://localhost

DB_CONNECTION=sqlite

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="TPC Website"
```

---

## Media Storage

Uploaded files are stored under `storage/app/public/` and served via the `storage` symlink in `public/`. Categories include:

- `about-slides/` — Homepage carousel images
- `news-images/` — News post featured images
- `org-chart/` — Org chart node photos
- `program-logos/`, `program-gallery/`, `program-people/`, `program-achievements/`
- `service-images/`, `service-content-images/`

---

## License

This project is proprietary software developed for **Talibon Polytechnic College**. All rights reserved.
