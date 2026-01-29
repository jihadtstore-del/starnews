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
        $category = trim($_POST['category'] ?? '');
        $brand = trim($_POST['brand_default'] ?? '');
        $model = trim($_POST['model_default'] ?? '');
        if ($name) {
            $stmt = $pdo->prepare('INSERT INTO equipment (name, category, brand_default, model_default, created_at) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute([$name, $category ?: null, $brand ?: null, $model ?: null]);
            $message = 'Equipment added.';
        }
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM equipment WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Equipment deleted.';
        }
    }
}

$equipment = $pdo->query('SELECT * FROM equipment ORDER BY created_at DESC')->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Manage Equipment</h4>
</div>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6>Add Equipment</h6>
        <form method="POST" class="row g-3">
            <?= csrf_field(); ?>
            <input type="hidden" name="action" value="create">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Equipment name" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="category" class="form-control" placeholder="Category">
            </div>
            <div class="col-md-3">
                <input type="text" name="brand_default" class="form-control" placeholder="Default brand">
            </div>
            <div class="col-md-3">
                <input type="text" name="model_default" class="form-control" placeholder="Default model">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Create Equipment</button>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="mb-3">Equipment List</h6>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equipment as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td><?= htmlspecialchars($item['brand_default']) ?></td>
                            <td><?= htmlspecialchars($item['model_default']) ?></td>
                            <td><?= htmlspecialchars($item['created_at']) ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Delete this equipment?');">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
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
