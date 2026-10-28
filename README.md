# Freelance Client Hub

A lightweight open-source portal for freelancers to manage clients, track projects, log billable hours, and generate PDF invoices.

**Anyone can register** and get their own private workspace.

## Live Demo

A public demo is being set up on Render. Once live, anyone can visit the URL and register their own account.

## Features

- Public registration
- Isolated data per user
- Clients, Projects, Hours logging, Invoices + PDF
- Dashboard stats
- Secure auth

## Self-Host

See Docker instructions below or import the SQL schema and configure `includes/config.php`.

### Docker
```bash
git clone https://github.com/exotic-SeanTHECoder/freelance-client-hub.git
cd freelance-client-hub
docker compose up -d
```

## Deploy to Render (one-click style)

The repo includes `render.yaml`. On Render you can create a Blueprint from this repo for a free web service + free Postgres/MySQL-compatible database.

## License

MIT — Created by [Sean](https://github.com/exotic-SeanTHECoder)
