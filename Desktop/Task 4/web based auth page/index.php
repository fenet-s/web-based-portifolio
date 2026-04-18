<?php

require_once __DIR__ . '/config/db.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    set_old([
        'username' => $username,
    ]);

    if ($username === '' || $password === '') {
        flash('error', 'Please enter both your username and password.');
        redirect('index.php');
    }

    $statement = db()->prepare('SELECT id, first_name, last_name, username, password_hash, department, gender, hobbies, others, created_at FROM users WHERE username = :username LIMIT 1');
    $statement->execute(['username' => $username]);
    $user = $statement->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        flash('error', 'Invalid username or password.');
        redirect('index.php');
    }

    unset($user['password_hash']);
    $user['hobbies'] = json_decode((string) ($user['hobbies'] ?? '[]'), true);
    if (!is_array($user['hobbies'])) {
        $user['hobbies'] = [];
    }

    $_SESSION['user'] = $user;
    clear_old();
    flash('success', 'Login successful. Welcome back, ' . $user['first_name'] . '!');
    redirect('dashboard.php');
}

$error = flash('error');
$success = flash('success');
$usernameValue = old('username', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="stylesheet" href="assets/styles.css">
  <script src="assets/app.js" defer></script>
</head>
<body class="auth-page">
  <main class="auth-shell">
    <section class="auth-card auth-card--login">
      <h1 class="auth-title">Login Form</h1>

      <?php if ($error): ?>
        <div class="notice notice--error"><?php echo e($error); ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="notice notice--success"><?php echo e($success); ?></div>
      <?php endif; ?>

      <form class="auth-form" method="post" action="index.php">
        <div class="field">
          <label for="username">Username:</label>
          <input class="text-input" type="text" id="username" name="username" value="<?php echo e($usernameValue); ?>" autocomplete="username" placeholder="Enter your username" required>
        </div>

        <div class="field" style="margin-top: 22px;">
          <label for="password">Password:</label>
          <input class="text-input" type="password" id="password" name="password" autocomplete="current-password" placeholder="Enter your password" required data-password>
        </div>

        <label class="inline-toggle" for="show-password-login">
          <input type="checkbox" id="show-password-login" data-toggle-password="#password">
          Show password
        </label>

        <div class="button-row">
          <button class="btn btn--primary" type="submit">Login</button>
          <button class="btn btn--danger" type="reset">Clear</button>
        </div>
      </form>

      <div class="auth-footer">
        <a href="register.php">Create New Account</a>
      </div>
    </section>
  </main>
</body>
</html>
