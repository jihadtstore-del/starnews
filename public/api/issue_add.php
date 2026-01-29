<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_login();
csrf_validate();

$equipmentId = (int)($_POST['equipment_id'] ?? 0);
$userId = (int)($_POST['user_id'] ?? 0);
$qty = (int)($_POST['qty'] ?? 0);
$unitNo = trim($_POST['unit_no'] ?? '');
$serial = trim($_POST['serial_no'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$locationId = (int)($_POST['location_id'] ?? 0);
$remarks = trim($_POST['remarks'] ?? '');

if ($equipmentId <= 0 || $userId <= 0 || $qty <= 0 || !$unitNo || !$phone || $locationId <= 0) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$stmt = db()->prepare('INSERT INTO issues (equipment_id, user_id, qty, unit_no, serial_no, phone, location_id, issue_date, status, remarks, created_by, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), "ISSUED", ?, ?, NOW())');
$stmt->execute([
    $equipmentId,
    $userId,
    $qty,
    $unitNo,
    $serial ?: null,
    $phone,
    $locationId,
    $remarks ?: null,
    current_user()['id'],
]);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
