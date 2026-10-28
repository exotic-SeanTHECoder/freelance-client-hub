# Freelance Client Hub

A lightweight, self-hosted open-source portal for freelancers to manage clients, track projects, log billable hours, and generate PDF invoices.

Built with **PHP**, **MySQL**, and **Vanilla JavaScript** — no heavy frameworks required.

## Features

- Client management (add, edit, delete)
- Project tracking linked to clients with hourly rates and status
- Billable hours logging with descriptions
- Invoice generation from uninvoiced hours
- Invoice status tracking (Draft, Sent, Paid, Overdue)
- Basic PDF invoice download
- Live dashboard with stats
- Session-based authentication (secure password hashing + CSRF)
- First-user setup script
- Self-hosted and privacy-focused
- Docker Compose support for one-command setup

## Tech Stack

- Backend: PHP 8.0+
- Database: MySQL 5.7+ / MariaDB
- Frontend: HTML5, CSS3, Vanilla JS
- PDF: Lightweight pure-PHP PDF generation (can be swapped for full FPDF)

## Setup Instructions

### Option A: Docker (recommended for quick start)

```bash
git clone https://github.com/exotic-SeanTHECoder/freelance-client-hub.git
cd freelance-client-hub
docker compose up -d
```

Then:
1. Copy `includes/config.example.php` to `includes/config.php`
2. Update DB credentials to match docker-compose.yml:
   - DB_HOST=db
   - DB_NAME=freelance_hub
   - DB_USER=hubuser
   - DB_PASS=hubpass
3. Visit http://localhost:8080/setup.php to create your admin account
4. Log in and start using the app

### Option B: Manual (PHP + MySQL)

1. **Requirements**
   - PHP 8.0 or higher with PDO MySQL extension
   - MySQL/MariaDB

2. **Clone and database**
   ```bash
   git clone https://github.com/exotic-SeanTHECoder/freelance-client-hub.git
   cd freelance-client-hub
   mysql -u youruser -p freelance_hub < sql/schema.sql
   ```

3. **Configuration**
   - Copy `includes/config.example.php` to `includes/config.php` and edit credentials

4. **Create admin**
   - `php -S localhost:8000`
   - Visit `/setup.php`

## Project Structure

See the repository for the full layout. Key files:
- `index.php` — Dashboard
- `clients.php` / `client_form.php`
- `projects.php` / `project_form.php`
- `hours.php`
- `invoices.php` / `invoice_pdf.php`
- `login.php`, `setup.php`

## Roadmap

- [x] Database schema
- [x] Authentication
- [x] Client CRUD
- [x] Project CRUD
- [x] Hours logging
- [x] Invoice generation + basic PDF
- [x] Dashboard with live stats
- [x] Docker Compose
- [ ] Full FPDF for richer invoices
- [ ] Additional reports / export

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

## License

MIT License — see [LICENSE](LICENSE)

---

Created by [Sean](https://github.com/exotic-SeanTHECoder) for the open-source community.
