<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pageTitle = 'Hours';

$projectFilter = (int)($_GET['project'] ?? 0);
$error = '';

// Projects for dropdown
$stmt = $pdo->prepare('SELECT id, name, hourly_rate FROM projects WHERE user_id = ? AND status = "active" ORDER BY name');
$stmt->execute([$_SESSION['user_id']]);
$projects = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_hours'])) {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $project_id  = (int)($_POST['project_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $hours       = (float)($_POST['hours'] ?? 0);
        $entry_date  = $_POST['entry_date'] ?? date('Y-m-d');

        if ($project_id === 0 || $description === '' || $hours <= 0) {
            $error = 'Project, description, and positive hours are required.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO time_entries (user_id, project_id, description, hours, entry_date) VALUES (?,?,?,?,?)');
            $stmt->execute([$_SESSION['user_id'], $project_id, $description, $hours, $entry_date]);
            header('Location: hours.php?saved=1' . ($projectFilter ? '&project='.$projectFilter : ''));
            exit;
        }
    }
}

// List entries
$sql = 'SELECT t.*, p.name AS project_name FROM time_entries t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ?';
$params = [$_SESSION['user_id']];
if ($projectFilter) {
    $sql .= ' AND t.project_id = ?';
    $params[] = $projectFilter;
}
$sql .= ' ORDER BY t.entry_date DESC, t.id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$entries = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h2>Log Hours</h2>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (isset($_GET['saved'])): ?><div class="success">Hours logged.</div><?php endif; ?>

    <?php if (empty($projects)): ?>
        <p>Create an active project first.</p>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="csrf" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="log_hours" value="1">
            <div class="form-group">
                <label>Project</label>
                <select name="project_id" required>
                    <?php foreach ($projects as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $projectFilter == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?> ($<?= number_format($p['hourly_rate'],2) ?>/hr)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="entry_date" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label>Hours</label>
                <input type="number" step="0.25" min="0.25" name="hours" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <button type="submit" class="btn">Log Hours</button>
        </form>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Time Entries <?= $projectFilter ? '(filtered)' : '' ?></h2>
    <?php if (empty($entries)): ?>
        <p>No hours logged yet.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Date</th><th>Project</th><th>Description</th><th>Hours</th><th>Invoiced</th></tr></thead>
            <tbody>
            <?php foreach ($entries as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['entry_date']) ?></td>
                    <td><?= htmlspecialchars($e['project_name']) ?></td>
                    <td><?= htmlspecialchars($e['description']) ?></td>
                    <td><?= number_format($e['hours'], 2) ?></td>
                    <td><?= $e['invoiced'] ? 'Yes' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 