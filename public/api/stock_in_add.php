<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_login();
csrf_validate();

$equipmentId = (int)($_POST['equipment_id'] ?? 0);
$brand = trim($_POST['brand'] ?? '');
$model = trim($_POST['model'] ?? '');
$qty = (int)($_POST['qty'] ?? 0);
$serial = trim($_POST['serial_no'] ?? '');
$stockInDate = $_POST['stock_in_date'] ?? '';
$remarks = trim($_POST['remarks'] ?? '');

if ($equipmentId <= 0 || $qty <= 0 || !$brand || !$model || !$stockInDate) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$stmt = db()->prepare('INSERT INTO stock_in (equipment_id, brand, model, qty, serial_no, stock_in_date, remarks, created_by, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
$stmt->execute([
    $equipmentId,
    $brand,
    $model,
    $qty,
    $serial ?: null,
    $stockInDate,
    $remarks ?: null,
    current_user()['id'],
]);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
