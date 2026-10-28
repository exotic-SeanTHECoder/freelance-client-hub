<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$project = null;
$error = '';

// Clients for dropdown
$stmt = $pdo->prepare('SELECT id, name FROM clients WHERE user_id = ? ORDER BY name');
$stmt->execute([$_SESSION['user_id']]);
$clients = $stmt->fetchAll();

if (empty($clients)) {
    header('Location: clients.php');
    exit;
}

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $_SESSION['user_id']]);
    $project = $stmt->fetch();
    if (!$project) { header('Location: projects.php'); exit; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $name        = trim($_POST['name'] ?? '');
        $client_id   = (int)($_POST['client_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $hourly_rate = (float)($_POST['hourly_rate'] ?? 0);
        $status      = $_POST['status'] ?? 'active';
        $start_date  = $_POST['start_date'] ?: null;
        $end_date    = $_POST['end_date'] ?: null;

        if ($name === '' || $client_id === 0) {
            $error = 'Name and client are required.';
        } else {
            if ($id) {
                $stmt = $pdo->prepare('UPDATE projects SET name=?, client_id=?, description=?, hourly_rate=?, status=?, start_date=?, end_date=? WHERE id=? AND user_id=?');
                $stmt->execute([$name, $client_id, $description, $hourly_rate, $status, $start_date, $end_date, $id, $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO projects (user_id, client_id, name, description, hourly_rate, status, start_date, end_date) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$_SESSION['user_id'], $client_id, $name, $description, $hourly_rate, $status, $start_date, $end_date]);
            }
            header('Location: projects.php?saved=1');
            exit;
        }
    }
}

$pageTitle = $id ? 'Edit Project' : 'Add Project';
require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h2><?= $pageTitle ?></h2>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf" value="<?= generateCsrfToken() ?>">
        <div class="form-group">
            <label>Project Name *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($project['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Client *</label>
            <select name="client_id" required>
                <option value="">Select client</option>
                <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($project['client_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Hourly Rate ($)</label>
            <input type="number" step="0.01" name="hourly_rate" value="<?= htmlspecialchars($project['hourly_rate'] ?? '0.00') ?>">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <?php foreach (['active','completed','on_hold','cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($project['status'] ?? 'active') === $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($project['start_date'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" value="<?= htmlspecialchars($project['end_date'] ?? '') ?>">
        </div>
        <button type="submit" class="btn">Save Project</button>
        <a href="projects.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 