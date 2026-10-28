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

## Tech Stack

- Backend: PHP 8.0+
- Database: MySQL 5.7+ / MariaDB
- Frontend: HTML5, CSS3, Vanilla JS
- PDF: Lightweight pure-PHP PDF generation (can be swapped for full FPDF)

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
   - Edit with your database credentials and a strong `APP_SECRET`

5. **Create the first admin account**
   - Start the server and visit `/setup.php`
   - Fill in the form (only works when no users exist yet)
   - After creation, setup is automatically disabled

6. **Run**
   - Point your web server document root to the project folder, or:
     ```bash
     php -S localhost:8000
     ```
   - Visit `http://localhost:8000` → you will be redirected to login

## Project Structure

```
freelance-client-hub/
├── assets/
│   ├── css/
│   └── js/
├── includes/
│   ├── auth.php
│   ├── config.example.php
│   ├── db.php
│   ├── header.php
│   └── footer.php
├── sql/
│   └── schema.sql
├── index.php
├── clients.php
├── client_form.php
├── projects.php
├── project_form.php
├── hours.php
├── invoices.php
├── invoice_pdf.php
├── login.php
├── logout.php
├── setup.php
├── .gitignore
├── LICENSE
└── README.md
```

## Roadmap

- [x] Database schema
- [x] Authentication (login/logout + first-user setup)
- [x] Client CRUD
- [x] Project CRUD
- [x] Hours logging
- [x] Invoice generation + basic PDF
- [x] Dashboard with live stats
- [ ] Full FPDF integration for richer invoices
- [ ] Responsive UI polish
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
