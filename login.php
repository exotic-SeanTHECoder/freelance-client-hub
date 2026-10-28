<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Username and password are required.';
        } elseif (login($username, $password)) {
            header('Location: /index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
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
    <title>Login — Freelance Client Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-box { max-width: 400px; margin: 4rem auto; background: var(--card); padding: 2rem; border-radius: 12px; border: 1px solid var(--border); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.35rem; font-weight: 500; }
        input[type=text], input[type=password] { width: 100%; padding: 0.65rem; border: 1px solid var(--border); border-radius: 6px; font-size: 1rem; }
        .error { background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; }
        button { width: 100%; padding: 0.75rem; background: var(--primary); color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: var(--primary-dark); }
    </style>
</head>
<body>
    <div class="login-box">
        <h1 style="text-align:center; margin-bottom:1.5rem;">Freelance Client Hub</h1>
        <h2 style="text-align:center; font-size:1.25rem; margin-bottom:1.5rem;">Sign in</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <p style="text-align:center; margin-top:1.5rem; font-size:0.9rem;">
            First time? <a href="setup.php">Create the admin account</a>
        </p>
    </div>
</body>
</html>
