<?php
require __DIR__ . '/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if ($title === '') {
        $errors[] = 'শিরোনাম আবশ্যক।';
    }
    if ($body === '') {
        $errors[] = 'বিস্তারিত আবশ্যক।';
    }
    if ($author === '') {
        $errors[] = 'লেখকের নাম আবশ্যক।';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO news (title, body, author, category) VALUES (:title, :body, :author, :category)');
        $stmt->execute([
            ':title' => $title,
            ':body' => $body,
            ':author' => $author,
            ':category' => $category,
        ]);
        $success = true;
    }
}

$stmt = $pdo->query('SELECT id, title, author, category, created_at FROM news ORDER BY created_at DESC LIMIT 8');
$recent = $stmt->fetchAll();

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Star News | অ্যাডমিন</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="brand">
                <span class="brand-dot"></span>
                <h1>Star News</h1>
            </div>
            <nav>
                <a href="index.php">হোম</a>
                <a href="admin.php" class="active">অ্যাডমিন</a>
            </nav>
        </div>
    </header>

    <main class="container admin">
        <section class="form-panel">
            <h2>নতুন সংবাদ যোগ করুন</h2>

            <?php if ($success): ?>
                <div class="alert success">সংবাদ সফলভাবে যোগ হয়েছে।</div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="alert error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" class="news-form">
                <label>
                    শিরোনাম
                    <input type="text" name="title" value="<?= e($_POST['title'] ?? '') ?>" required>
                </label>
                <label>
                    ক্যাটাগরি
                    <input type="text" name="category" value="<?= e($_POST['category'] ?? '') ?>" placeholder="রাজনীতি, ক্রীড়া">
                </label>
                <label>
                    লেখক
                    <input type="text" name="author" value="<?= e($_POST['author'] ?? '') ?>" required>
                </label>
                <label>
                    বিস্তারিত
                    <textarea name="body" rows="6" required><?= e($_POST['body'] ?? '') ?></textarea>
                </label>
                <button type="submit">সংবাদ প্রকাশ করুন</button>
            </form>
        </section>

        <section class="recent-panel">
            <h2>সাম্প্রতিক সংবাদ</h2>
            <div class="recent-list">
                <?php if (!$recent): ?>
                    <p>এখনও কোনো সংবাদ যোগ হয়নি।</p>
                <?php else: ?>
                    <?php foreach ($recent as $item): ?>
                        <article>
                            <h3><?= e($item['title']) ?></h3>
                            <p><?= e($item['category'] ?: 'সাধারণ') ?> · <?= e($item['author']) ?></p>
                            <time datetime="<?= e($item['created_at']) ?>">
                                <?= e(date('d M Y, h:i A', strtotime($item['created_at']))) ?>
                            </time>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
