# Freelance Client Hub

A lightweight, self-hosted open-source portal for freelancers to manage clients, track projects, log billable hours, and generate PDF invoices.

Built with **PHP**, **MySQL**, and **Vanilla JavaScript** — no heavy frameworks required.

## Features

- Client management (add, edit, view)
- Project tracking linked to clients
- Billable hours logging with descriptions
- Invoice generation (PDF) from logged hours
- Invoice status tracking (Draft, Sent, Paid, Overdue)
- Simple dashboard overview
- Session-based authentication
- Self-hosted and privacy-focused

## Tech Stack

- Backend: PHP 8.0+
- Database: MySQL 5.7+ / MariaDB
- Frontend: HTML5, CSS3, Vanilla JS
- PDF: FPDF (lightweight pure PHP)

## Setup Instructions

1. **Requirements**
   - PHP 8.0 or higher with PDO MySQL extension
   - MySQL/MariaDB
   - Web server (Apache with mod_rewrite recommended, or Nginx, or built-in PHP server for development)

2. **Clone the repository**
   ```bash
   git clone https://github.com/exotic-SeanTHECoder/freelance-client-hub.git
   cd freelance-client-hub
   ```

3. **Database**
   - Create a MySQL database, e.g. `freelance_hub`
   - Import the schema:
     ```bash
     mysql -u youruser -p freelance_hub < sql/schema.sql
     ```

4. **Configuration**
   - Copy `includes/config.example.php` to `includes/config.php`
   - Edit with your database credentials and a strong secret for sessions/CSRF

5. **Create initial admin user**
   - Run the setup script (coming soon) or insert manually into the `users` table (password hashed with `password_hash()`)

6. **Run**
   - Point your web server document root to the project folder, or:
     ```bash
     php -S localhost:8000
     ```
   - Visit `http://localhost:8000`

## Project Structure

```
freelance-client-hub/
├── assets/
│   ├── css/
│   └── js/
├── includes/
│   ├── config.example.php
│   └── db.php
├── sql/
│   └── schema.sql
├── index.php
├── .gitignore
├── LICENSE
└── README.md
```

## Roadmap

- [x] Database schema
- [ ] Authentication (login/logout)
- [ ] Client CRUD
- [ ] Project CRUD
- [ ] Hours logging
- [ ] Invoice generation (PDF)
- [ ] Dashboard with stats
- [ ] Responsive UI
- [ ] Docker Compose for easy self-hosting

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

1. Fork the repo
2. Create a feature branch
3. Commit your changes
4. Open a PR

## License

MIT License — see [LICENSE](LICENSE)

---

Created by [Sean](https://github.com/exotic-SeanTHECoder) for the open-source community.
