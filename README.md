# Freelance Client Hub

A lightweight open-source portal for freelancers to manage clients, track projects, log billable hours, and generate PDF invoices.

**Anyone can register** and get their own private workspace.

## Live Demo

**https://clienthub-fdf4.onrender.com**

> Note: This is a free Render instance. It may take 30–50 seconds to wake up if it has been idle. The free database expires November 27, 2026 unless upgraded.

## Features

- Public user registration (each user gets isolated data)
- Client management (add, edit, delete)
- Project tracking with hourly rates and status
- Billable hours logging
- Invoice generation from uninvoiced hours + status tracking
- Basic PDF invoices
- Live dashboard with stats
- Secure session auth + CSRF protection
- Docker support

## Tech Stack

- Backend: PHP 8.0+
- Database: PostgreSQL (demo) / MySQL (self-host)
- Frontend: HTML5, CSS3, Vanilla JS

## Self-Host

### Docker
```bash
git clone https://github.com/exotic-SeanTHECoder/freelance-client-hub.git
cd freelance-client-hub
docker compose up -d
```

### Manual
1. Import `sql/schema.sql` (MySQL) or `sql/schema_postgres.sql`
2. Copy `includes/config.example.php` to `includes/config.php` and edit
3. Visit `/register.php` to create an account

## License

MIT License

Created by [Sean](https://github.com/exotic-SeanTHECoder)
