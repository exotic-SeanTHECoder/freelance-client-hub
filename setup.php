<?php
/**
 * First-user setup. Only works when the users table is empty.
 * After the first account is created this page disables itself.
 */
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = false;

// Check if any users already exist
$stmt = $pdo->query('SELECT COUNT(*) AS cnt FROM users');
$userCount = (int)$stmt->fetch()['cnt'];

if ($userCount > 0) {
    $error = 'An admin account already exists. Setup is disabled.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userCount === 0) {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            $error = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hash]);
            $success = true;
        }
    }
}

$csrf = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup — Freelance Client Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .setup-box { max-width: 440px; margin: 3rem auto; background: var(--card); padding: 2rem; border-radius: 12px; border: 1px solid var(--border); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.35rem; font-weight: 500; }
        input { width: 100%; padding: 0.65rem; border: 1px solid var(--border); border-radius: 6px; font-size: 1rem; }
        .error { background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; }
        .success { background: #dcfce7; color: #166534; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; }
        button { width: 100%; padding: 0.75rem; background: var(--primary); color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: var(--primary-dark); }
    </style>
</head>
<body>
    <div class="setup-box">
        <h1 style="text-align:center; margin-bottom:0.5rem;">First-time Setup</h1>
        <p style="text-align:center; margin-bottom:1.5rem; color:#64748b;">Create the administrator account</p>

        <?php if ($success): ?>
            <div class="success">Account created successfully. You can now <a href="login.php">log in</a>.</div>
        <?php else: ?>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($userCount === 0): ?>
            <form method="POST">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (min 8 chars)</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" id="confirm" name="confirm" required>
                </div>
                <button type="submit">Create Admin Account</button>
            </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
