# Eleven Soft Backend Refactoring Test

## About the Project

This repository is a Laravel 10+ backend API designed to demonstrate and evaluate clean code, modern PHP, and scalable architecture
practices. The project applies basic Clean Architecture and containerized development.

**Key Features:**

- Clean Architecture
- PHP (8.3+), strict typing, and SOLID principles
- Automated tests (Pest/PHPUnit)
- Full Docker-based local environment
- API documentation with Scramble (OpenAPI/Swagger)
- Developer tools: Mailhog (email testing), Clockwork (debug/profiling)

---

## Stack & Main Tools

- **Laravel 10+** (API only)
- **PHP 8.3+**
- **MySQL** (default: port 3336)
- **Redis** (default: port 6379)
- **Nginx** (default: port 8081)
- **Mailhog** (local email capture: http://localhost:8025)
- **Clockwork** (API profiling/debugging: http://localhost:8081/clockwork/)
- **Scramble** (http://localhost:8081/docs/api)
- **Swagger** (http://localhost:8081/api/documentation)
- **Pest/PHPUnit** (tests)

---

## Getting Started (Local Development)

### Prerequisites

- Docker & Docker Compose
- Git

### Quickstart

1. **Clone the repository:**
   ```bash
   git clone https://github.com/elevensoft-dev/backend-refactoring-test.git
   cd backend-refactoring-test
   ```
2. **Copy and configure environment variables:**
   ```bash
   cp .env.example .env
   ```
3. **Start the project:**
   ```bash
   docker compose up
   ```
    - The API will be available at: http://localhost:8081
    - Mailhog UI: http://localhost:8025
    - Swagger docs: http://localhost:8081/api/documentation

4. **(Optional) Access the API container shell:**
   ```bash
   docker compose exec php-api bash
   ```

---

## Services Explained

### Mailhog (Email Testing)

- **URL:** http://localhost:8025
- All outgoing emails are captured here for testing. No real emails are sent in development.
- SMTP is preconfigured to use Mailhog (port 1025).

### Clockwork (API Profiling & Debugging)

- **URL:** http://localhost:8081/clockwork/app
- **Purpose:** Profile requests, database queries, cache, queue, and more.
- **How to use:**
    - Make any API request (via browser, Postman, etc.).
    - Inspect request/response, queries, cache, etc.

### Scramble (API Documentation)

- **URL:** http://localhost:8081/docs/api
- Generates and serves OpenAPI (Swagger) docs for all API endpoints.
- Keep docs updated when changing endpoints, requests, or responses.

---

## Project Structure

- `00-infrastructure/` – DevOps, Docker, docs
- `app/` – Main application code (Controllers, UseCases, DTOs, Domain, etc.)
- `routes/` – API and web routes
- `database/` – Migrations, seeders, factories
- `tests/` – Automated tests (Pest/PHPUnit)
- `config/` – Laravel and package configs

**Architecture:**

- Controllers: Only orchestrate requests, DTOs, and UseCases (no business logic)
- UseCases: All business logic lives here, always receive a DTO as single input
- DTOs: Data transfer between layers
- Form Requests: All input validation
- Query Builders: Dedicated for complex queries

---

## Testing

- Run all tests:
  ```bash
  docker compose exec php-api php artisan test
  ```
- Feature tests:
  ```bash
  docker compose exec php-api php artisan test --testsuite=Feature
  ```
- Unit tests:
  ```bash
  docker compose exec php-api php artisan test --testsuite=Unit
  ```

---
