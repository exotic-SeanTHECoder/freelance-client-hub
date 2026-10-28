<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$client = null;
$error = '';

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $_SESSION['user_id']]);
    $client = $stmt->fetch();
    if (!$client) { header('Location: clients.php'); exit; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $notes   = trim($_POST['notes'] ?? '');

        if ($name === '') {
            $error = 'Name is required.';
        } else {
            if ($id) {
                $stmt = $pdo->prepare('UPDATE clients SET name=?, email=?, company=?, phone=?, address=?, notes=? WHERE id=? AND user_id=?');
                $stmt->execute([$name, $email, $company, $phone, $address, $notes, $id, $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO clients (user_id, name, email, company, phone, address, notes) VALUES (?,?,?,?,?,?,?)');
                $stmt->execute([$_SESSION['user_id'], $name, $email, $company, $phone, $address, $notes]);
            }
            header('Location: clients.php?saved=1');
            exit;
        }
    }
}

$pageTitle = $id ? 'Edit Client' : 'Add Client';
require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h2><?= $pageTitle ?></h2>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf" value="<?= generateCsrfToken() ?>">
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($client['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Company</label>
            <input type="text" name="company" value="<?= htmlspecialchars($client['company'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($client['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($client['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address"><?= htmlspecialchars($client['address'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes"><?= htmlspecialchars($client['notes'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn">Save Client</button>
        <a href="clients.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 