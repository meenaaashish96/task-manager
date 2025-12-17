## Mini Task Management System – Laravel Machine Test

This project is a **mini task management system** built with **Laravel 11**, implementing both **web (Blade)** and **REST API** features.

It follows the given machine-test requirements:

- User authentication (web & API via Sanctum).
- Task CRUD (title, description, status, due date, user relation).
- Background job + scheduled command to email reminders for tasks due tomorrow.
- RESTful JSON APIs for tasks.
- Web dashboard with Blade, Tailwind CSS (Breeze), pagination, and basic filters.

---

### 1. Tech Stack

- **Backend**: PHP 8.2+, Laravel 11
- **Auth**: Laravel Breeze (web), Laravel Sanctum (API tokens)
- **Database**: SQLite (default) or MySQL (configurable)
- **Queues**: Database queue driver
- **Mail**: Any Laravel-supported mail driver (Mailtrap/local SMTP recommended)
- **Frontend**: Blade + Tailwind CSS (via Vite)

---

### 2. Getting Started

#### 2.1. Clone & Install Dependencies

```bash
git clone <your-repo-url> task-manager
cd task-manager

composer install
npm install
```

#### 2.2. Environment Setup

1. Copy the example `.env`:

```bash
cp .env.example .env
```

2. Generate the app key:

```bash
php artisan key:generate
```

3. Configure database in `.env` (default is SQLite). For SQLite (recommended for quick setup):

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

Create the file if it does not exist:

```bash
touch database/database.sqlite
```

For MySQL, set:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

4. Configure mail (for reminder emails) in `.env`, for example with Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@example.com"
MAIL_FROM_NAME="Task Manager"
```

5. Configure queue:

```env
QUEUE_CONNECTION=database
```

#### 2.3. Migrate & Seed

```bash
php artisan migrate
php artisan db:seed
```

The seeder will:

- Ensure at least one test user exists.
- Insert a **sample task** so you can immediately see data on the dashboard.

#### 2.4. Build Frontend Assets

In a separate terminal:

```bash
npm run dev   # for local development with Vite
```

or

```bash
npm run build # for production build
```

#### 2.5. Run the Application

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

You can log in using a user you register via the UI, or any user created by the seeder.

---

### 3. Features Overview

#### 3.1. Authentication

- Standard **registration, login, logout, password reset** flows powered by Laravel Breeze.
- Only **authenticated & verified users** can access:
  - `/dashboard`
  - All task routes (`/tasks`, `/tasks/{id}`, etc.).

#### 3.2. Task Management (Web – Blade)

- **Model**: `App\Models\Task`
  - `title` (string, required)
  - `description` (text, nullable)
  - `status` (`pending`, `in-progress`, `completed`)
  - `due_date` (date)
  - `user_id` (foreign key to `users` table)

- **Migration**: `database/migrations/2025_12_17_085516_create_tasks_table.php`
  - Uses `enum` for `status`.
  - Cascades deletes on user removal.

- **Controller**: `App\Http\Controllers\TaskController`
  - `index()` – lists current user’s tasks (paginated, filterable by `status` and `due_date`).
  - `store()` – creates a new task for the logged-in user.
  - `edit()` – shows edit form for user’s own task.
  - `update()` – updates a task (with ownership check).
  - `destroy()` – deletes a task (with ownership check).

- **Views**:
  - `resources/views/tasks/index.blade.php`
    - Dashboard-style page with:
      - Add New Task form (title, description, status, due date).
      - Filters (status, due date).
      - Paginated task table with status badges and actions (Edit/Delete).
  - `resources/views/tasks/edit.blade.php`
    - Simple edit form with Tailwind styling.

- **Routes**: `routes/web.php`

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::resource('tasks', TaskController::class)->except(['create', 'show']);
});
```

#### 3.3. API (Sanctum)

- **Auth**:

  - `POST /api/login`
    - Request: `{ "email": "...", "password": "..." }`
    - Response: `{ "token": "plain-text-api-token" }`
    - Uses Laravel Sanctum to create a personal access token.

- **Protected Endpoints** (all require `Authorization: Bearer {token}` header):

  - `GET /api/tasks`
    - Returns paginated list of tasks for the authenticated user.
  - `POST /api/tasks`
    - Creates new task.
    - Body: `title`, `description` (optional), `status`, `due_date`.
  - `PUT /api/tasks/{id}`
    - Updates an existing task belonging to the user.
  - `DELETE /api/tasks/{id}`
    - Deletes an existing task belonging to the user.

- **Controller**: `App\Http\Controllers\Api\TaskController`
  - Returns **JSON responses** with `status`, `message`, and `data` fields.
  - Uses proper HTTP status codes (`201` for create, `403` for unauthorized, etc.).

---

### 4. Background Job, Queue & Scheduler

#### 4.1. Reminder Command

- Command: `App\Console\Commands\SendTaskReminders`
  - Signature: `tasks:send-reminders`
  - Behavior:
    - Finds tasks due **tomorrow** with status not `completed`.
    - For each task, queues an email to the owning user.

#### 4.2. Scheduling

- `routes/console.php`:

```php
Schedule::command('tasks:send-reminders')->dailyAt('08:00');
```

Laravel’s scheduler must be hooked into your system cron (production) by running the standard `php artisan schedule:run` every minute.

#### 4.3. Queue Worker

To process queued emails:

```bash
php artisan queue:work
```

Ensure `QUEUE_CONNECTION=database` and that `php artisan queue:table && php artisan migrate` has been run (already included in migrations).

---

### 5. Database Design & Relationships

- `users` table (default Laravel users).
- `tasks` table:
  - `user_id` references `users.id` (`foreignId()->constrained()->onDelete('cascade')`).
  - Relationship:
    - `User` hasMany `Task`
    - `Task` belongsTo `User`

---

### 6. Running Tests (optional)

Laravel’s default feature tests for auth and profile are available. You can run:

```bash
php artisan test
```

---

### 7. Notes & Assumptions

- Only **logged-in users** can manage their tasks (web + API).
- No separate admin role is implemented by default, but the structure allows easy extension:
  - Add `role` column to `users` table.
  - Add authorization checks in controllers and/or policies.
- UI uses **Tailwind** via Laravel Breeze for a clean, modern look; further UI polish can be added easily.

---

### 8. Quick Start Summary

1. `composer install && npm install`
2. Copy `.env`, configure DB, mail, queue; run `php artisan key:generate`.
3. `php artisan migrate && php artisan db:seed`
4. `npm run dev` (or `npm run build`) and `php artisan serve`
5. Visit `http://localhost:8000`, register/login, and manage tasks from `/dashboard`.  
6. For API usage, call `POST /api/login` to get a token, then use the task endpoints with `Authorization: Bearer {token}`.

