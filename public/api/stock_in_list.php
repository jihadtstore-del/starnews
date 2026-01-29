<?php
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$equipmentId = (int)($_GET['equipment_id'] ?? 0);
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$stmt = db()->prepare('SELECT COUNT(*) FROM stock_in WHERE equipment_id = ?');
$stmt->execute([$equipmentId]);
$totalRecords = (int)$stmt->fetchColumn();

$stmt = db()->prepare('SELECT stock_in.*, equipment.name AS equipment_name
    FROM stock_in
    JOIN equipment ON equipment.id = stock_in.equipment_id
    WHERE stock_in.equipment_id = ?
    ORDER BY stock_in_date DESC, stock_in.id DESC
    LIMIT ? OFFSET ?');
$stmt->bindValue(1, $equipmentId, PDO::PARAM_INT);
$stmt->bindValue(2, $perPage, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll();

$summaryStmt = db()->prepare('SELECT COALESCE(SUM(qty), 0) AS total_qty, MAX(stock_in_date) AS last_date FROM stock_in WHERE equipment_id = ?');
$summaryStmt->execute([$equipmentId]);
$summary = $summaryStmt->fetch();

header('Content-Type: application/json');
echo json_encode([
    'records' => $records,
    'pagination' => [
        'total' => $totalRecords,
        'page' => $page,
        'per_page' => $perPage,
    ],
    'summary' => [
        'total_records' => $totalRecords,
        'total_qty' => (int)($summary['total_qty'] ?? 0),
        'last_date' => $summary['last_date'] ?? null,
    ],
]);
?>
