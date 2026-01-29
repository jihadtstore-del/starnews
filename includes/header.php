<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/csrf.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/../assets/css/app.css" rel="stylesheet">
    <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token()) ?>">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard.php"><?= htmlspecialchars(APP_NAME) ?></a>
    <div class="ms-auto text-white">
        <?php if (current_user()): ?>
            <span class="me-3"><?= htmlspecialchars(current_user()['name']) ?></span>
            <a class="btn btn-sm btn-outline-light" href="<?= BASE_URL ?>/logout.php">Logout</a>
        <?php endif; ?>
    </div>
</nav>
<div class="container-fluid py-3">
