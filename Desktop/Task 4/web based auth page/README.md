# Web-Based Auth Page

This is a small PHP authentication project with a clean front-end and a PostgreSQL-backed user table.

It includes:

- login and registration pages
- a protected dashboard
- server-side validation
- password hashing with `password_hash()`
- session-based authentication
- a simple front-end password toggle/match helper

## Tech Stack

- PHP
- PostgreSQL
- HTML/CSS
- JavaScript
- PHP 8+ with PDO PostgreSQL support

## Project Structure

- `index.php` / `login.php` — login page and login handler
- `register.php` / `signup.php` — registration page and registration handler
- `dashboard.php` — protected page shown after login
- `logout.php` — destroys the session
- `config/bootstrap.php` — session, env, and helper functions
- `config/db.php` — PDO PostgreSQL connection
- `assets/styles.css` — all styling
- `assets/app.js` — password toggle and live password matching
- `database/schema.sql` — `users` table schema

## Requirements

- PostgreSQL installed and running
- PHP with the `pdo_pgsql` extension enabled
- A web server for PHP, such as Apache, XAMPP, Laragon, or PHP's built-in server

## Setup

1. Make sure PostgreSQL is running.

2. Create a database named:
   - `auth_assignment`

3. Run the SQL file to create the table:
   - `database/schema.sql`

4. Update the `.env` file so the database values match your PostgreSQL setup:

   ```env
   DB_HOST=localhost
   DB_PORT=5432
   DB_NAME=auth_assignment
   DB_USER=postgres
   DB_PASS=your_postgresql_password
   ```

   If your PostgreSQL user has no password, leave `DB_PASS` blank.

5. Start the app in your browser.

   If you have PHP installed, you can use the built-in server from the project folder and open:
   - `http://127.0.0.1:8000/index.php`
   - `http://127.0.0.1:8000/register.php`

   On Windows, you can also double-click `start.bat` to launch the local PHP server.

   If you prefer Apache, place the project inside your web root and open the matching local URL.

   If Apache uses a different port, include it in the URL, for example:
   - `http://localhost:8080/index.php`

## How It Works

- A new user registers with profile details, username, and password.
- The password is hashed before being stored in PostgreSQL.
- The login page verifies the password with `password_verify()`.
- After login, the user is redirected to the dashboard.
- Logging out clears the session.

## Database Notes

- The `users` table stores the profile fields shown in the form.
- `hobbies` is stored as `JSONB` in PostgreSQL.
- `created_at` and `updated_at` are automatically maintained by PostgreSQL.

## GitHub Notes

- Do not commit `.env` if it contains real credentials.
- `schema.sql` is safe to commit and share.

## Common Issues

- If `php` is not recognized in PowerShell, use the PHP that ships with XAMPP, Laragon, or another local web stack.
- If PostgreSQL will not start, another service may already be using port `5432`.
- If the browser shows a 404, make sure your web server is pointing at the project folder.

## License

This project is for learning and assignment use.
