<?php
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$q = trim($_GET['q'] ?? '');
$like = '%' . $q . '%';

$stmt = db()->prepare('SELECT id, name FROM equipment WHERE name LIKE ? ORDER BY name');
$stmt->execute([$like]);
$items = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode(['items' => $items]);
?>
