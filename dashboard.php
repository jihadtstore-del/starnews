<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_login();

$user = current_user();

$message = $_SESSION['dashboard_message'] ?? null;
$message_type = $_SESSION['dashboard_message_type'] ?? 'success';
unset($_SESSION['dashboard_message'], $_SESSION['dashboard_message_type']);

$issues = [];

if ($pdo) {
    $statement = $pdo->query('SELECT * FROM equipment_issues ORDER BY issue_date DESC LIMIT 20');
    $issues = $statement->fetchAll();
} else {
    $issues = [
        [
            'id' => 1,
            'user_name' => 'Uzzol (It)',
            'equipment_name' => 'DP to DP Cable',
            'quantity' => 10,
            'phone' => '01700000000',
            'unit_no' => 'Unit-01',
            'serial_no' => 'SN-8899',
            'issue_date' => '2026-01-17 12:48:55',
            'status' => 'Issued',
        ],
        [
            'id' => 2,
            'user_name' => 'Pervez (Main)',
            'equipment_name' => 'XLR Male to Female',
            'quantity' => 12,
            'phone' => '01800000000',
            'unit_no' => 'Unit-03',
            'serial_no' => 'SN-7788',
            'issue_date' => '2026-01-16 23:45:54',
            'status' => 'Pending',
        ],
    ];
}

$stats = [
    'issued' => 982,
    'returned' => 26,
    'pending' => 956,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="top-bar">
        <div class="brand">
            <span class="logo">SN</span>
            <div>
                <h1>Equipment Tracker</h1>
                <p>Star News Inventory Desk</p>
            </div>
        </div>
        <div class="user-info">
            <span><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></span>
            <span class="badge"><?php echo htmlspecialchars(strtoupper($user['role'])); ?></span>
            <a class="link" href="logout.php">Logout</a>
        </div>
    </header>

    <main class="dashboard">
        <?php if ($message): ?>
            <div class="alert <?php echo $message_type === 'error' ? 'alert-error' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <section class="stats">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div>
                    <p>Issued</p>
                    <h2><?php echo $stats['issued']; ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div>
                    <p>Returned</p>
                    <h2><?php echo $stats['returned']; ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div>
                    <p>Pending</p>
                    <h2><?php echo $stats['pending']; ?></h2>
                </div>
            </div>
        </section>

        <section class="filters">
            <div class="pill-group">
                <button class="pill active">All</button>
                <button class="pill">Pending</button>
                <button class="pill">Returned</button>
            </div>
            <button class="primary" type="button" onclick="window.print()">Print</button>
        </section>

        <?php if ($user['role'] === 'admin'): ?>
            <section class="card">
                <div class="card-title">
                    <span class="title-icon">➕</span>
                    <h3>Issue Equipment</h3>
                </div>
                <form class="issue-form">
                    <input type="text" placeholder="User Name">
                    <input type="text" placeholder="Equipment Name">
                    <input type="number" placeholder="Quantity">
                    <input type="text" placeholder="Phone">
                    <input type="text" placeholder="Unit No">
                    <input type="text" placeholder="Serial No">
                    <button type="button">Issue</button>
                </form>
            </section>
        <?php endif; ?>

        <section class="card">
            <div class="card-title">
                <h3>Change Password</h3>
            </div>
            <form class="issue-form" method="post" action="change_password.php">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit">Update Password</button>
            </form>
        </section>

        <?php if ($user['role'] === 'admin'): ?>
            <section class="card">
                <div class="card-title">
                    <h3>Create User Account</h3>
                </div>
                <form class="issue-form" method="post" action="register_user.php">
                    <input type="text" name="full_name" placeholder="Full Name" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Temporary Password" required>
                    <select name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit">Create Account</button>
                </form>
                <?php if (!$pdo): ?>
                    <p class="muted">Database connection required to create new accounts.</p>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <section class="card">
            <div class="card-title">
                <h3>Search Records</h3>
            </div>
            <form class="search-form">
                <input type="text" placeholder="Search User / Equipment">
                <button type="button">Search</button>
            </form>
        </section>

        <section class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Qty</th>
                            <th>Phone</th>
                            <th>Unit</th>
                            <th>Serial</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <?php if ($user['role'] === 'admin'): ?>
                                <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($issues as $index => $issue): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($issue['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($issue['equipment_name']); ?></td>
                                <td><?php echo htmlspecialchars($issue['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($issue['phone']); ?></td>
                                <td><?php echo htmlspecialchars($issue['unit_no']); ?></td>
                                <td><?php echo htmlspecialchars($issue['serial_no']); ?></td>
                                <td><?php echo htmlspecialchars($issue['issue_date']); ?></td>
                                <td>
                                    <span class="status <?php echo strtolower($issue['status']); ?>">
                                        <?php echo htmlspecialchars($issue['status']); ?>
                                    </span>
                                </td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <td class="actions">
                                        <button class="icon-button" title="Return">✔</button>
                                        <button class="icon-button" title="Edit">✎</button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
