<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# NexusTask - Backend 

NexusTask Backend is a robust RESTful API built with Laravel 11, designed to power the NexusTask frontend ecosystem. It handles secure user authentication, complex task management logic, and advanced data recovery through soft deletes.

## Key Technical Features

- **Secure Authentication:** Implemented using Laravel Sanctum for lightweight, token-based security.

- **Soft Delete Engine:** Advanced data management allowing users to move tasks to a "Trash" state without permanent data loss.

- **Eloquent Resource Management:** High-performance database queries optimized for a Single Page Application (SPA) environment.

- **Resource Ownership:** Strict middleware checks to ensure users can only view, edit, or delete their own data.

- **RESTful Routing:** Clean API structure following industry-standard HTTP methods.

## Tech Stack

- **Framework:** Laravel 11
- **Language:** PHP 8.2+
- **Database:** MySQL / PostgreSQL
- **Security:** Laravel Sanctum
- **Environment:** Composer

## Installation & Setup

Follow these steps to set up the API on your local development environment.

1. Clone the Repository

```bash
git clone https://github.com/your-username/nexustask-backend.git
cd nexustask-backend
```

2. Install Dependencies

```bash
composer install
```

3. Environment Configuration

Copy the example environment file and update your database credentials:

```bash
cp .env.example .env
```

Open `.env` and update the following lines:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexustask_db
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate Application Key

```bash
php artisan key:generate
```

5. Run Migrations

Ensure your database server is running, then execute the migrations:

```bash
php artisan migrate
```

6. Start the Server

```bash
php artisan serve
```

The API will be available at http://127.0.0.1:8000.

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|---|---|---|
| POST | /api/register | Create a new account |
| POST | /api/login | Authenticate and receive token |
| POST | /api/logout | Revoke current access token |

### Task Management (Requires Auth)

| Method | Endpoint | Description |
|---|---|---|
| GET | /api/tasks | Get all active tasks for user |
| POST | /api/tasks | Create a new task |
| PUT | /api/tasks/{id} | Update task details or status |
| DELETE | /api/tasks/{id} | Move task to trash (Soft Delete) |
| GET | /api/tasks/trash | View only deleted tasks |
| PUT | /api/tasks/{id}/restore | Bring task back from trash |

## Model Logic (Soft Deletes)

To support the "Trash" feature, ensure the `Task` model uses the `SoftDeletes` trait:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model {
	use SoftDeletes;
}
```

## Security Architecture

This API utilizes Laravel Sanctum middleware. Every request to the `/api/tasks` routes must include the following header:

```
Authorization: Bearer {YOUR_TOKEN}
```

Developed by Vishmith Suranjaya — Computer Science Undergraduate at Uva Wellassa University
