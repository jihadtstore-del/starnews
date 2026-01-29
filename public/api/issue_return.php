<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_login();
require_role(['admin', 'store']);
csrf_validate();

$issueId = (int)($_POST['issue_id'] ?? 0);
$condition = trim($_POST['condition'] ?? '');
$returnRemarks = trim($_POST['return_remarks'] ?? '');

if ($issueId <= 0) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid issue']);
    exit;
}

$remarks = trim($returnRemarks . ($condition ? ' | Condition: ' . $condition : ''));

$stmt = db()->prepare('UPDATE issues SET return_date = NOW(), status = "RETURNED", remarks = ? WHERE id = ?');
$stmt->execute([$remarks ?: null, $issueId]);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
