<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pageTitle = 'Clients';

// Delete
if (isset($_GET['delete']) && verifyCsrfToken($_GET['csrf'] ?? null)) {
    $stmt = $pdo->prepare('DELETE FROM clients WHERE id = ? AND user_id = ?');
    $stmt->execute([(int)$_GET['delete'], $_SESSION['user_id']]);
    header('Location: clients.php?deleted=1');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM clients WHERE user_id = ? ORDER BY name');
$stmt->execute([$_SESSION['user_id']]);
$clients = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Clients</h2>
        <a href="client_form.php" class="btn">+ Add Client</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="success">Client deleted.</div>
    <?php endif; ?>
    <?php if (isset($_GET['saved'])): ?>
        <div class="success">Client saved.</div>
    <?php endif; ?>

    <?php if (empty($clients)): ?>
        <p>No clients yet. <a href="client_form.php">Add your first client</a>.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($clients as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['company'] ?? '') ?></td>
                    <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                    <td class="actions">
                        <a href="client_form.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <a href="clients.php?delete=<?= $c['id'] ?>&csrf=<?= generateCsrfToken() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this client and all related projects?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 