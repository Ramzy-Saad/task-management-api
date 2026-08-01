# Task Management RESTful API

A production-ready, scalable, and fully documented RESTful API built with **Laravel 11**, **Laravel Sanctum**, and **OpenAPI/Swagger**.

---

## Table of Contents

1. [Features](#features)
2. [Tech Stack & Architecture](#tech-stack--architecture)
3. [Prerequisites](#prerequisites)
4. [Environment Setup](#environment-setup)
5. [Installation Steps](#installation-steps)
6. [Database Setup & Seeding](#database-setup--seeding)
7. [Running the Application](#running-the-application)
8. [API Documentation (Swagger)](#api-documentation-swagger)
9. [Running Tests](#running-tests)
10. [API Endpoints Overview](#api-endpoints-overview)

---

## Features

* **Authentication:** Token-based authentication using Laravel Sanctum (Register, Login, Logout).
* **Project Management:** Full CRUD operations with Policy authorization (Users manage their own projects).
* **Task Management:** Advanced task management with status, priority, due dates, sorting, search, and pagination.
* **Dashboard Analytics:** Aggregated project and task performance metrics.
* **OpenAPI 3.0 / Swagger UI:** Interactive browser documentation built with PHP 8 Attributes (`l5-swagger`).
* **100% JSON API Architecture:** Standardized API responses with zero Blade views.

---

## Tech Stack & Architecture

* **Framework:** Laravel 13.x
* **PHP:** 8.3+
* **Database:** PostgreSQL / MySQL
* **Authentication:** Laravel Sanctum
* **API Documentation:** `darkaonline/l5-swagger` (OpenAPI 3.0)
* **Architecture Pattern:** Action-Domain-Responder / Service-Repository Pattern with API Resources, Form Requests, and Policies.

---

## Prerequisites

Ensure you have the following installed on your system:

* **PHP:** `>= 8.3` (Extensions required: `mbstring`, `pdo`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
* **Composer:** `>= 2.5`
* **Database Engine:** MySQL 8.0+ or PostgreSQL 14+
* **Tooling (Optional):** Git, Postman, Docker

---

## Environment Setup

Create a `.env` file in the project root by copying the `.env.example`:

```bash
cp .env.example .env
```
Configure your .env variables:
``` bash
APP_NAME="Task Management API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=secret

# Queue Configuration
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@taskmanagement.com"
MAIL_FROM_NAME="${APP_NAME}"

# Swagger API Specs Host
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000/api
```
---

## Installation Steps
Run the following commands in order:
1. Clone the Repository


* git clone [https://github.com/Ramzy-Saad/task-management-api.git](https://github.com/Ramzy-Saad/task-management-api.git)
* cd task-management-api
* Install PHP Dependencies
```bash
composer install
```
* Generate Application Encryption Key
```bash
php artisan key:generate
```
* Create Database
Create an empty database matching the DB_DATABASE name defined in your .env file.

## Database Setup & Seeding
Run migrations to build table schemas and populate seed data:
### Run database migrations
```bash
php artisan migrate
```
# (Optional) Seed the database with sample users, projects, and tasks
```bash
php artisan db:seed
```
---
## Running the Application
1. Start Laravel Development Server
``` bash
php artisan serve
```
The API will be live at: http://localhost:8000/api

API Documentation (Swagger)
This API includes automated interactive documentation using OpenAPI 3.0 via l5-swagger.

Generate OpenAPI Specs JSON
```Bash
php artisan l5-swagger:generate
```

Access Interactive Swagger UI
Open your browser and navigate to:
```Bash
http://localhost:8000/api/documentation
```
