<?php
require_once __DIR__ . '/../../includes/auth.php';
require_login();
require_role(['admin', 'store']);
require_once __DIR__ . '/../../includes/csrf.php';

$pdo = db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        if ($name) {
            $stmt = $pdo->prepare('INSERT INTO locations (name) VALUES (?)');
            $stmt->execute([$name]);
            $message = 'Location added.';
        }
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM locations WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Location deleted.';
        }
    }
}

$locations = $pdo->query('SELECT * FROM locations ORDER BY name')->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Manage Locations</h4>
</div>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6>Add Location</h6>
        <form method="POST" class="row g-3">
            <?= csrf_field(); ?>
            <input type="hidden" name="action" value="create">
            <div class="col-md-6">
                <input type="text" name="name" class="form-control" placeholder="Location name" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Create Location</button>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="mb-3">Locations</h6>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $location): ?>
                        <tr>
                            <td><?= htmlspecialchars($location['name']) ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Delete this location?');">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$location['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
