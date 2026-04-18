# Web-Based Auth Page

This is a small PHP authentication project with a clean front-end and a MySQL-backed user table.

It includes:

- login and registration pages
- a protected dashboard
- server-side validation
- password hashing with `password_hash()`
- session-based authentication
- a simple front-end password toggle/match helper

## Tech Stack

- PHP
- MySQL
- HTML/CSS
- JavaScript
- XAMPP / Apache

## Project Structure

- `index.php` / `login.php` — login page and login handler
- `register.php` / `signup.php` — registration page and registration handler
- `dashboard.php` — protected page shown after login
- `logout.php` — destroys the session
- `config/bootstrap.php` — session, env, and helper functions
- `config/db.php` — PDO MySQL connection
- `assets/styles.css` — all styling
- `assets/app.js` — password toggle and live password matching
- `database/schema.sql` — `users` table schema

## Requirements

- XAMPP installed
- Apache running
- MySQL running
- PHP with the `pdo_mysql` extension enabled

## Setup with XAMPP

1. Copy the project folder to XAMPP’s web root:
   - from: `C:\Users\HP\Desktop\Task 4\web based auth page`
   - to: `C:\xampp\htdocs\auth-page`

2. Start **Apache** and **MySQL** in the XAMPP Control Panel.

3. Open phpMyAdmin in your browser:
   - `http://localhost/phpmyadmin`

4. Create a database named:
   - `auth_assignment`

5. Open the database, click **Import**, and import:
   - `database/schema.sql`

6. Update the `.env` file so the database values match your XAMPP setup:

   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=auth_assignment
   DB_USER=root
   DB_PASS=your_mysql_password
   DB_CHARSET=utf8mb4
   ```

   If your MySQL `root` user has no password, leave `DB_PASS` blank.

7. Open the app in your browser:
   - `http://localhost/auth-page/index.php`
   - `http://localhost/auth-page/register.php`

   If Apache uses a different port, include it in the URL, for example:
   - `http://localhost:8080/auth-page/index.php`

## How It Works

- A new user registers with profile details, username, and password.
- The password is hashed before being stored in MySQL.
- The login page verifies the password with `password_verify()`.
- After login, the user is redirected to the dashboard.
- Logging out clears the session.

## Database Notes

- The `users` table stores the profile fields shown in the form.
- `hobbies` is stored as JSON in MySQL.
- `created_at` and `updated_at` are automatically maintained by MySQL.

## GitHub Notes

- Do not commit `.env` if it contains real credentials.
- `schema.sql` is safe to commit and share.

## Common Issues

- If `php` is not recognized in PowerShell, use XAMPP Apache instead of the built-in PHP server.
- If MySQL will not start, another service may already be using port `3306`.
- If the browser shows a 404, make sure the project is inside `C:\xampp\htdocs\auth-page`.

## License

This project is for learning and assignment use.
