<?php
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$equipmentId = (int)($_GET['equipment_id'] ?? 0);
$userQ = trim($_GET['user_q'] ?? '');
$equipmentQ = trim($_GET['equipment_q'] ?? '');
$status = trim($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = (int)($_GET['per_page'] ?? 10);
$perPage = max(5, min(100, $perPage));
$offset = ($page - 1) * $perPage;

$conditions = ['issues.equipment_id = :equipment_id'];
$params = ['equipment_id' => $equipmentId];

if ($userQ !== '') {
    $conditions[] = 'users.name LIKE :user_q';
    $params['user_q'] = '%' . $userQ . '%';
}
if ($equipmentQ !== '') {
    $conditions[] = 'equipment.name LIKE :equipment_q';
    $params['equipment_q'] = '%' . $equipmentQ . '%';
}
if ($status === 'ISSUED' || $status === 'RETURNED') {
    $conditions[] = 'issues.status = :status';
    $params['status'] = $status;
}

$where = implode(' AND ', $conditions);

$countStmt = db()->prepare("SELECT COUNT(*)
    FROM issues
    JOIN users ON users.id = issues.user_id
    JOIN equipment ON equipment.id = issues.equipment_id
    WHERE $where");
$countStmt->execute($params);
$totalRecords = (int)$countStmt->fetchColumn();

$listStmt = db()->prepare("SELECT issues.*, users.name AS user_name, equipment.name AS equipment_name, locations.name AS location_name
    FROM issues
    JOIN users ON users.id = issues.user_id
    JOIN equipment ON equipment.id = issues.equipment_id
    LEFT JOIN locations ON locations.id = issues.location_id
    WHERE $where
    ORDER BY issues.issue_date DESC, issues.id DESC
    LIMIT :limit OFFSET :offset");
$listStmt->bindValue(':equipment_id', $equipmentId, PDO::PARAM_INT);
if (isset($params['user_q'])) {
    $listStmt->bindValue(':user_q', $params['user_q']);
}
if (isset($params['equipment_q'])) {
    $listStmt->bindValue(':equipment_q', $params['equipment_q']);
}
if (isset($params['status'])) {
    $listStmt->bindValue(':status', $params['status']);
}
$listStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$listStmt->execute();
$records = $listStmt->fetchAll();

header('Content-Type: application/json');
echo json_encode([
    'records' => $records,
    'pagination' => [
        'total' => $totalRecords,
        'page' => $page,
        'per_page' => $perPage,
    ],
]);
?>
