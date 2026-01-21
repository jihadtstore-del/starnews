<?php
$config = $GLOBALS['config'] ?? ['app' => ['name' => 'Technician Store']];
$user = Auth::user();
?>
<!doctype html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($config['app']['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php?route=dashboard"><?php echo htmlspecialchars($config['app']['name']); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($user): ?>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=admin-dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=technicians">Technicians</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=equipments">Equipments</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=admin-issues">Daily Issues</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=reports">Reports</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=issue-create">New Issue</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=issue-history">Issue History</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?route=logout">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
