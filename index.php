<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star News Equipment Tracker - Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-body">
    <main class="auth-card">
        <div class="auth-header">
            <span class="logo">SN</span>
            <div>
                <h1>Star News Equipment Tracker</h1>
                <p>Sign in to manage or view equipment issues.</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form class="auth-form" method="post" action="login.php">
            <label>
                Username
                <input type="text" name="username" placeholder="Enter username" required>
            </label>
            <label>
                Password
                <input type="password" name="password" placeholder="Enter password" required>
            </label>
            <button type="submit">Sign in</button>
        </form>

        <div class="auth-hint">
            <p>Demo Admin: <strong>admin</strong> / <strong>admin123</strong></p>
            <p>Demo User: <strong>user</strong> / <strong>user123</strong></p>
        </div>
    </main>
</body>
</html>
