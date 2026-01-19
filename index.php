<?php
require __DIR__ . '/db.php';

$stmt = $pdo->query('SELECT id, title, body, author, category, created_at FROM news ORDER BY created_at DESC LIMIT 12');
$articles = $stmt->fetchAll();

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
    <title>Star News</title>
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
                <a href="admin.php">অ্যাডমিন</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <div>
                <p class="tag">আজকের শীর্ষ সংবাদ</p>
                <h2>সর্বশেষ আপডেট এবং বিশ্বস্ত সংবাদ এক জায়গায়</h2>
                <p class="lead">Star News আপনার জন্য নিয়ে এসেছে গুরুত্বপূর্ণ ঘটনাগুলোর দ্রুত আপডেট। প্রতিদিন নতুন সংবাদ যোগ করুন এবং পাঠকদের কাছে পৌঁছে দিন।</p>
            </div>
            <div class="hero-card">
                <h3>লাইভ আপডেট</h3>
                <p>রিপোর্টারদের তৈরি ব্রেকিং নিউজ, স্থানীয় এবং আন্তর্জাতিক খবর।</p>
                <ul>
                    <li>রাজনীতি, অর্থনীতি, প্রযুক্তি</li>
                    <li>ক্রীড়া ও বিনোদন</li>
                    <li>বিশেষ প্রতিবেদন</li>
                </ul>
            </div>
        </section>

        <section class="news-grid">
            <?php if (!$articles): ?>
                <div class="empty-state">
                    <h3>এখনও কোনো সংবাদ নেই</h3>
                    <p>প্রথম সংবাদ যোগ করতে <a href="admin.php">অ্যাডমিন পেজে যান</a>।</p>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <article class="news-card">
                        <div class="card-meta">
                            <span><?= e($article['category'] ?: 'সাধারণ') ?></span>
                            <time datetime="<?= e($article['created_at']) ?>">
                                <?= e(date('d M Y, h:i A', strtotime($article['created_at']))) ?>
                            </time>
                        </div>
                        <h3><?= e($article['title']) ?></h3>
                        <p><?= e(mb_strimwidth($article['body'], 0, 180, '...')) ?></p>
                        <div class="author">রিপোর্টার: <?= e($article['author']) ?></div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>© <?= date('Y') ?> Star News. সর্বস্বত্ব সংরক্ষিত।</p>
        </div>
    </footer>
</body>
</html>
