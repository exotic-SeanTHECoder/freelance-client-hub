<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pageTitle = 'Projects';

if (isset($_GET['delete']) && verifyCsrfToken($_GET['csrf'] ?? null)) {
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ? AND user_id = ?');
    $stmt->execute([(int)$_GET['delete'], $_SESSION['user_id']]);
    header('Location: projects.php?deleted=1');
    exit;
}

$stmt = $pdo->prepare('
    SELECT p.*, c.name AS client_name
    FROM projects p
    JOIN clients c ON p.client_id = c.id
    WHERE p.user_id = ?
    ORDER BY p.status, p.name
');
$stmt->execute([$_SESSION['user_id']]);
$projects = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Projects</h2>
        <a href="project_form.php" class="btn">+ Add Project</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?><div class="success">Project deleted.</div><?php endif; ?>
    <?php if (isset($_GET['saved'])): ?><div class="success">Project saved.</div><?php endif; ?>

    <?php if (empty($projects)): ?>
        <p>No projects yet. <a href="project_form.php">Add your first project</a>.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Name</th><th>Client</th><th>Rate</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['client_name']) ?></td>
                    <td>$<?= number_format($p['hourly_rate'], 2) ?></td>
                    <td><?= ucfirst(str_replace('_', ' ', $p['status'])) ?></td>
                    <td class="actions">
                        <a href="project_form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <a href="hours.php?project=<?= $p['id'] ?>" class="btn btn-sm">Hours</a>
                        <a href="projects.php?delete=<?= $p['id'] ?>&csrf=<?= generateCsrfToken() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this project?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 