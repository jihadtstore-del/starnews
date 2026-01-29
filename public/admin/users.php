<?php
require_once __DIR__ . '/../../includes/auth.php';
require_login();
require_role(['admin']);
require_once __DIR__ . '/../../includes/csrf.php';

$pdo = db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $password = $_POST['password'] ?? '';

        if ($name && $email && $password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, password_hash, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
            $stmt->execute([$name, $email, $phone, $hash, $role]);
            $message = 'User created.';
        }
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'User deleted.';
        }
    }
}

$users = $pdo->query('SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Manage Users</h4>
</div>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6>Add User</h6>
        <form method="POST" class="row g-3">
            <?= csrf_field(); ?>
            <input type="hidden" name="action" value="create">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="phone" class="form-control" placeholder="Phone">
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="user">User</option>
                    <option value="store">Store Manager</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Create User</button>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="mb-3">Users</h6>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role']) ?></span></td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Delete this user?');">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
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
