<?php

require_once __DIR__ . '/config/bootstrap.php';
require_login();

$user = current_user();
$success = flash('success');
$hobbies = $user['hobbies'] ?? [];
if (!is_array($hobbies)) {
    $decoded = json_decode((string) $hobbies, true);
    $hobbies = is_array($decoded) ? $decoded : [];
}

$createdAt = 'Not available';
if (!empty($user['created_at'])) {
    $timestamp = strtotime((string) $user['created_at']);
    if ($timestamp !== false) {
        $createdAt = date('M j, Y h:i A', $timestamp);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="dashboard-page">
  <main class="dashboard-shell">
    <section class="auth-card dashboard-card">
      <h1 class="auth-title">Welcome, <?php echo e($user['first_name']); ?>!</h1>

      <?php if ($success): ?>
        <div class="notice notice--success"><?php echo e($success); ?></div>
      <?php endif; ?>

      <p class="dashboard-note">You are now logged in. Here is your saved profile information from the database.</p>

      <div class="dashboard-meta">
        <div class="meta-card">
          <span class="meta-label">Full Name</span>
          <div class="meta-value"><?php echo e($user['first_name'] . ' ' . $user['last_name']); ?></div>
        </div>

        <div class="meta-card">
          <span class="meta-label">Username</span>
          <div class="meta-value"><?php echo e($user['username']); ?></div>
        </div>

        <div class="meta-card">
          <span class="meta-label">Department</span>
          <div class="meta-value"><?php echo e($user['department']); ?></div>
        </div>

        <div class="meta-card">
          <span class="meta-label">Gender</span>
          <div class="meta-value"><?php echo e($user['gender']); ?></div>
        </div>

        <div class="meta-card">
          <span class="meta-label">Hobbies</span>
          <div class="meta-value hobby-tags">
            <?php foreach ($hobbies as $hobby): ?>
              <span class="tag"><?php echo e($hobby); ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="meta-card">
          <span class="meta-label">Registered On</span>
          <div class="meta-value"><?php echo e($createdAt); ?></div>
        </div>

        <div class="meta-card field--full">
          <span class="meta-label">Others</span>
          <div class="meta-value"><?php echo e($user['others'] ?: 'No extra notes provided.'); ?></div>
        </div>
      </div>

      <div class="dashboard-actions">
        <a class="btn btn--danger" href="logout.php">Logout</a>
      </div>
    </section>
  </main>
</body>
</html>
