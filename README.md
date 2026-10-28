# Freelance Client Hub

A lightweight, self-hosted open-source portal for freelancers to manage clients, track projects, log billable hours, and generate PDF invoices.

**Anyone can register** and start using their own private workspace.

Built with **PHP**, **MySQL**, and **Vanilla JavaScript**.

## Features

- Public user registration (each user gets their own isolated data)
- Client management (add, edit, delete)
- Project tracking with hourly rates and status
- Billable hours logging
- Invoice generation from uninvoiced hours + status tracking
- Basic PDF invoices
- Live dashboard with stats
- Secure session auth + CSRF protection
- Docker support

## Live Demo

Coming soon — a public demo will be available shortly where anyone can register and try it.

## Quick Start (Self-Host)

### Docker
```bash
git clone https://github.com/exotic-SeanTHECoder/freelance-client-hub.git
cd freelance-client-hub
docker compose up -d
```
Then configure `includes/config.php` with the Docker DB credentials and visit the site to register.

### Manual
1. Import `sql/schema.sql` into MySQL
2. Copy `includes/config.example.php` to `includes/config.php` and edit
3. Run with PHP's built-in server or Apache/Nginx
4. Visit `/register.php` to create an account

## License

MIT License

Created by [Sean](https://github.com/exotic-SeanTHECoder)
