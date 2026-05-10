# PreNacer — Project Guide for Agents

## Overview

PreNacer is a custom PHP MVC web application for prenatal care management. It handles patients, doctors, diagnoses, diagnostic exploration reports (ultrasound), first-trimester reports, consultations, and medical consent forms with digital signatures.

## Tech Stack

- **Language**: Plain PHP (no framework) — requires PHP 7.4+
- **Database**: MySQL/MariaDB via PDO (singleton in `core/Database.php`)
- **Frontend**: Bootstrap 5 (CDN), FontAwesome 6 (CDN), custom Apple-style CSS in `views/layouts/header.php`
- **Libraries**: PHPMailer (email), DomPDF (PDF generation)
- **Package manager**: Composer (`composer.json`)
- **Server**: Apache with `.htaccess` rewriting (XAMPP/WAMP-style local dev)

## Project Structure

```
prenacersistem/
├── index.php              # Entry point — session, autoload, routes, resolve
├── .htaccess              # Rewrites all requests to index.php?path=$1
├── composer.json          # phpmailer, dompdf
├── init_db.sql            # Full database schema + seed data
├── config/                # Gitignored — database.php, mail.php (constants)
├── core/                  # Framework core
│   ├── Router.php         # Simple router (GET/POST → controller method)
│   ├── Controller.php     # Base controller (render view, redirect)
│   ├── Database.php       # PDO singleton
│   └── Mailer.php         # PHPMailer wrapper
├── controllers/           # 12 controllers, all extend Controller
├── models/                # 13 models — plain PDO queries, no ORM
├── views/                 # PHP templates, organized by feature
│   └── layouts/           # header.php, sidebar.php, footer.php
├── helpers/               # Auth, Session, Url, Validator (static classes)
├── assets/                # Static files (css/, js/, img/)
├── logos/                 # Logo SVGs
└── storage/               # File uploads (signatures, PDFs)
```

## Architecture Patterns

### MVC Flow
1. **`index.php`** — starts session, loads Composer autoloader, requires all core + controller files, registers routes, calls `$router->resolve()`
2. **Router** — matches `$_SERVER['REQUEST_METHOD']` + path (strips `BASE_URL` prefix and query strings), instantiates controller, calls method
3. **Controller** — extends `Controller` base class, uses `$this->render('view/path', $data)` and `$this->redirect('/path')`
4. **Model** — each model gets PDO via `Database::getInstance()->getConnection()`, methods return arrays via `fetch()` / `fetchAll()`
5. **View** — PHP files that include `layouts/header.php` + `layouts/sidebar.php` at top, `layouts/footer.php` at bottom. Controllers set `$title` variable before rendering.

### View Layout Convention
Every view follows this pattern:
```php
<?php
$title = "Page Title";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>
<!-- Page content here -->
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
```

`header.php` outputs the full `<head>` and inline CSS styles (CSS custom properties for theming). `sidebar.php` renders the sidebar navigation (role-based visibility) plus the top navbar and flash message alerts. `footer.php` closes `<main>`, `<div class="main-wrapper">`, `<body>`, and includes Bootstrap JS.

### Config Files (Gitignored)
- `config/database.php` — defines `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_CHARSET`, `DB_USER`, `DB_PASS`
- `config/mail.php` — defines `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`

Both are gitignored (`.gitignore` has `config/`). The `setup_db.php` and `init_db.sql` files are used to provision the database initially.

## Role-Based Access Control

| Role ID | Name            | Permissions |
|---------|-----------------|-------------|
| 1       | Superadministrador | Full access to everything |
| 2       | Administrador   | User management, assignments, reports, patients |
| 3       | Jefe de Médicos | Supervisor: diagnoses, doctors, reports |
| 4       | Médico          | Own patients, own diagnoses, consultations |

Role constants are defined in `helpers/Auth.php` as `Auth::ROLE_SUPERADMIN`, etc. Views check `$roleId` to conditionally show sidebar links.

## Helpers (Static Utility Classes)

| Helper      | Purpose |
|-------------|---------|
| `Auth`      | Login/logout, `check()`, `user()`, `hasRole($id)`, role constants |
| `Session`   | `set/get/has/remove`, `getFlash()` for one-time messages, `destroy()` |
| `Url`       | `base()` returns base path, `to($path)` returns full URL |
| `Validator` | Input validation (`required()`, `email()`, `numeric()`, etc.) |

## Key Conventions

- **Naming**: Spanish everywhere — variable names, DB tables/columns, routes, view files (`usuarios`, `pacientes`, `diagnosticos`)
- **Database table prefix**: none — raw table names (`usuarios`, `pacientes`, `diagnosticos`, etc.)
- **SQL**: Prepared statements via PDO everywhere (manual, no query builder)
- **Error handling**: `display_errors = 1` for dev, `error_log()` for mail failures, try/catch in `Controller::render()`
- **Flash messages**: Set via `Session::set('error', ...)` or `Session::set('success', ...)`, displayed in `sidebar.php` via `Session::getFlash()`
- **Redirects**: `$this->redirect('/path')` in controllers (handles base URL prefix)
- **URL generation**: Always use `Url::to('/path')` in views to handle base path prefix
- **Authentication guard**: Check `Auth::check()` at the start of every controller method that requires login
- **Assets**: CSS/JS placed in `assets/` folder, referenced as `assets/css/custom.css` (no leading slash — relative to project root)

## Common Commands

```bash
# Install dependencies
composer install

# Run tests (no test suite configured — manual testing via browser)

# Linting / static analysis — none configured
```

The project is served via Apache/XAMPP. There is no build step, no bundler, no npm. It's a traditional server-rendered PHP app.

## Database Notes

The schema is in `init_db.sql`. Key tables:
- `usuarios` — users with `rol_id` FK to `roles`
- `pacientes` — patients
- `diagnosticos` — diagnoses linking patients to doctors
- `asignaciones` — doctor-patient assignments
- `informes_exploracion` — ultrasound exploration reports (1–3 per trimester)
- `reportes_1er_trimestre` — first trimester reports
- `consultas` — medical consultations
- `catalogo_consentimientos` — consent form templates
- `consentimientos_asignados` — assigned consent forms
- `registro_firmas` — digital signature records
- `password_resets` — password reset tokens
- `bitacora` — activity log

Default superadmin: `superadmin@medical.com` / `Admin123!` (seeded in `init_db.sql`)
