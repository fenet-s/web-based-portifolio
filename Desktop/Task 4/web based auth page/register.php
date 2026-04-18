<?php

require_once __DIR__ . '/config/db.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$departments = [
    'Computer Science',
    'Information Technology',
    'Engineering',
    'Business Administration',
    'Arts and Design',
];

$genderOptions = ['Male', 'Female', 'Other'];
$hobbyOptions = ['Reading', 'Sports', 'Music', 'Travel'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim((string) ($_POST['first_name'] ?? ''));
    $lastName = trim((string) ($_POST['last_name'] ?? ''));
    $department = trim((string) ($_POST['department'] ?? 'Computer Science'));
    $gender = trim((string) ($_POST['gender'] ?? ''));
    $others = trim((string) ($_POST['others'] ?? ''));
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
    $selectedHobbies = isset($_POST['hobbies']) && is_array($_POST['hobbies']) ? array_values($_POST['hobbies']) : [];
    $hobbies = array_values(array_intersect($hobbyOptions, $selectedHobbies));

    set_old([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'department' => $department,
        'gender' => $gender,
        'others' => $others,
        'username' => $username,
        'hobbies' => $hobbies,
    ]);

    $errors = [];

    if ($firstName === '') {
        $errors[] = 'First name is required.';
    }

    if ($lastName === '') {
        $errors[] = 'Last name is required.';
    }

    if (!in_array($department, $departments, true)) {
        $errors[] = 'Please choose a valid department.';
    }

    if (!in_array($gender, $genderOptions, true)) {
        $errors[] = 'Please select your gender.';
    }

    if (count($hobbies) === 0) {
        $errors[] = 'Please select at least one hobby.';
    }

    if ($username === '' || !preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
        $errors[] = 'Username must be 3-30 characters and use only letters, numbers, or underscores.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($password !== $confirmPassword) {
      $errors[] = 'Passwords do not match.';
    }

    if (!empty($errors)) {
        flash('error', implode(' ', $errors));
        redirect('register.php');
    }

    $statement = db()->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
    $statement->execute(['username' => $username]);

    if ($statement->fetch()) {
        flash('error', 'That username is already taken. Please choose another one.');
        redirect('register.php');
    }

    $insert = db()->prepare(
        'INSERT INTO users (first_name, last_name, department, gender, hobbies, others, username, password_hash)
         VALUES (:first_name, :last_name, :department, :gender, :hobbies, :others, :username, :password_hash)'
    );

    $insert->execute([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'department' => $department,
        'gender' => $gender,
        'hobbies' => json_encode($hobbies, JSON_UNESCAPED_UNICODE),
        'others' => $others !== '' ? $others : null,
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    clear_old();
    flash('success', 'Registration completed successfully. You can now log in.');
    redirect('index.php');
}

$error = flash('error');
$success = flash('success');
$oldFirstName = old('first_name', '');
$oldLastName = old('last_name', '');
$oldDepartment = old('department', 'Computer Science');
$oldGender = old('gender', '');
$oldOthers = old('others', '');
$oldUsername = old('username', '');
$oldHobbies = old('hobbies', []);
if (!is_array($oldHobbies)) {
    $oldHobbies = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="assets/styles.css">
  <script src="assets/app.js" defer></script>
</head>
<body class="auth-page">
  <main class="auth-shell">
    <section class="auth-card auth-card--register">
      <h1 class="auth-title">Registration Form</h1>

      <?php if ($error): ?>
        <div class="notice notice--error"><?php echo e($error); ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="notice notice--success"><?php echo e($success); ?></div>
      <?php endif; ?>

      <form class="auth-form" method="post" action="register.php">
        <div class="form-grid form-grid--three">
          <div class="field">
            <label for="first_name">First Name</label>
            <input class="text-input" type="text" id="first_name" name="first_name" value="<?php echo e($oldFirstName); ?>" placeholder="First name" required>
          </div>

          <div class="field">
            <label for="last_name">Last Name</label>
            <input class="text-input" type="text" id="last_name" name="last_name" value="<?php echo e($oldLastName); ?>" placeholder="Last name" required>
          </div>

          <div class="field">
            <label for="department">Department</label>
            <select class="select-input" id="department" name="department" required>
              <?php foreach ($departments as $department): ?>
                <option value="<?php echo e($department); ?>" <?php echo selected($oldDepartment, $department); ?>><?php echo e($department); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <fieldset class="field fieldset-box">
            <legend>Gender</legend>
            <div class="option-list">
              <?php foreach ($genderOptions as $option): ?>
                <label class="option-item">
                  <input type="radio" name="gender" value="<?php echo e($option); ?>" <?php echo checked($oldGender, $option); ?> required>
                  <span><?php echo e($option); ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <fieldset class="field fieldset-box">
            <legend>Hobbies</legend>
            <div class="option-list">
              <?php foreach ($hobbyOptions as $option): ?>
                <label class="option-item">
                  <input type="checkbox" name="hobbies[]" value="<?php echo e($option); ?>" <?php echo checked($oldHobbies, $option); ?>>
                  <span><?php echo e($option); ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <div class="field">
            <label for="others">Others</label>
            <textarea class="textarea-input" id="others" name="others" placeholder="Any extra information..."><?php echo e($oldOthers); ?></textarea>
          </div>
        </div>

        <div class="section-heading">Account Details</div>
        <div class="form-grid form-grid--three">
          <div class="field">
            <label for="username">Username</label>
            <input class="text-input" type="text" id="username" name="username" value="<?php echo e($oldUsername); ?>" autocomplete="username" placeholder="Choose a username" required>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input class="text-input" type="password" id="password" name="password" autocomplete="new-password" placeholder="Create a password" required data-password>
          </div>

          <div class="field">
            <label for="confirm_password">Confirm Password</label>
            <input class="text-input" type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" placeholder="Repeat your password" required data-confirm-password>
            <small class="form-hint" data-password-status></small>
          </div>
        </div>

        <label class="inline-toggle" for="show-password-register" style="margin-top: 18px;">
          <input type="checkbox" id="show-password-register" data-toggle-password="#password, #confirm_password">
          Show password
        </label>

        <div class="button-row">
          <button class="btn btn--primary" type="submit">Register</button>
          <button class="btn btn--danger" type="reset">Clear</button>
        </div>
      </form>

      <div class="auth-footer">
        Already have an account? <a href="index.php">Login here</a>
      </div>
    </section>
  </main>
</body>
</html>
