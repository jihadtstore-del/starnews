<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
$isPartial = isset($_GET['partial']);
if (!$isPartial) {
    require_once __DIR__ . '/../includes/header.php';
}

$equipmentId = (int)($_GET['id'] ?? 0);
if ($equipmentId <= 0) {
    echo '<div class="alert alert-warning">Equipment not found.</div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$stmt = db()->prepare('SELECT * FROM equipment WHERE id = ?');
$stmt->execute([$equipmentId]);
$equipment = $stmt->fetch();
if (!$equipment) {
    echo '<div class="alert alert-warning">Equipment not found.</div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$totals = [
    'total_in_qty' => 0,
    'total_issued_qty' => 0,
    'returned_qty' => 0,
    'in_use_qty' => 0,
    'available_balance' => 0,
];

$totalsStmt = db()->prepare('SELECT
    (SELECT COALESCE(SUM(qty), 0) FROM stock_in WHERE equipment_id = :equipment_id) AS total_in_qty,
    (SELECT COALESCE(SUM(qty), 0) FROM issues WHERE equipment_id = :equipment_id) AS total_issued_qty,
    (SELECT COALESCE(SUM(qty), 0) FROM issues WHERE equipment_id = :equipment_id AND status = "RETURNED") AS returned_qty,
    (SELECT COALESCE(SUM(qty), 0) FROM issues WHERE equipment_id = :equipment_id AND status = "ISSUED") AS in_use_qty
');
$totalsStmt->execute(['equipment_id' => $equipmentId]);
$totals = $totalsStmt->fetch() ?: $totals;
$totals['available_balance'] = $totals['total_in_qty'] - $totals['in_use_qty'];

$locations = db()->query('SELECT id, name FROM locations ORDER BY name')->fetchAll();
$users = db()->query('SELECT id, name FROM users ORDER BY name')->fetchAll();
?>
<div class="equipment-header mb-3">
    <h4 class="mb-1"><?= htmlspecialchars($equipment['name']) ?></h4>
    <p class="text-muted mb-0">Category: <?= htmlspecialchars($equipment['category'] ?? 'General') ?></p>
</div>

<div class="sticky-summary bg-white shadow-sm mb-3 p-3 rounded">
    <div class="row g-3">
        <div class="col-6 col-lg">
            <div class="summary-card">
                <span>Total Stock In</span>
                <strong><?= (int)$totals['total_in_qty'] ?></strong>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="summary-card">
                <span>Total Issued</span>
                <strong><?= (int)$totals['total_issued_qty'] ?></strong>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="summary-card">
                <span>Returned</span>
                <strong><?= (int)$totals['returned_qty'] ?></strong>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="summary-card">
                <span>In Use</span>
                <strong><?= (int)$totals['in_use_qty'] ?></strong>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="summary-card">
                <span>Available Balance</span>
                <strong><?= (int)$totals['available_balance'] ?></strong>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-stock-in" type="button">Stock In</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-stock-out" type="button">Stock Out</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-usage" type="button">Balance/Usage</button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tab-stock-in" role="tabpanel">
        <div class="sticky-form bg-white border rounded p-3 mb-3">
            <h6 class="mb-3">Add Stock In</h6>
            <form id="stock-in-form" class="row g-3">
                <?= csrf_field(); ?>
                <input type="hidden" name="equipment_id" value="<?= (int)$equipmentId ?>">
                <div class="col-md-3">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($equipment['brand_default'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($equipment['model_default'] ?? '') ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qty</label>
                    <input type="number" name="qty" class="form-control" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Serial No</label>
                    <input type="text" name="serial_no" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock In Date</label>
                    <input type="date" name="stock_in_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Remarks</label>
                    <input type="text" name="remarks" class="form-control">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Save Stock In</button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3 mb-3" id="stock-in-summary"></div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="stock-in-table">
                        <thead class="table-light">
                            <tr>
                                <th>SL No</th>
                                <th>Equipment Name</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Qty</th>
                                <th>Serial No</th>
                                <th>Stock In Date</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination" id="stock-in-pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-stock-out" role="tabpanel">
        <div class="sticky-form bg-white border rounded p-3 mb-3">
            <h6 class="mb-3">Issue Equipment</h6>
            <form id="issue-form" class="row g-3">
                <?= csrf_field(); ?>
                <input type="hidden" name="equipment_id" value="<?= (int)$equipmentId ?>">
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= (int)$user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qty</label>
                    <input type="number" name="qty" class="form-control" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit No</label>
                    <input type="text" name="unit_no" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Serial No</label>
                    <input type="text" name="serial_no" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select" required>
                        <option value="">Select Location</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?= (int)$location['id'] ?>"><?= htmlspecialchars($location['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Remarks</label>
                    <input type="text" name="remarks" class="form-control">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Issue Item</button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Search by User</label>
                        <input type="text" id="issue-search-user" class="form-control" placeholder="User name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search by Equipment</label>
                        <input type="text" id="issue-search-equipment" class="form-control" placeholder="Equipment name">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary" id="issue-search-btn" type="button">Search</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="issue-table">
                        <thead class="table-light">
                            <tr>
                                <th>SL No</th>
                                <th>User Name</th>
                                <th>Equipment Name</th>
                                <th>Qty</th>
                                <th>Unit No</th>
                                <th>Serial No</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination" id="issue-pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-usage" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Currently Issued (Not Returned)</h6>
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="usage-table">
                        <thead class="table-light">
                            <tr>
                                <th>SL No</th>
                                <th>User Name</th>
                                <th>Location</th>
                                <th>Qty</th>
                                <th>Unit No</th>
                                <th>Issue Date</th>
                                <th>Purpose/Remarks</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="return-form">
                <?= csrf_field(); ?>
                <input type="hidden" name="issue_id" id="return-issue-id">
                <div class="modal-header">
                    <h5 class="modal-title">Return Equipment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-select">
                            <option value="good">Good</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Return Remarks</label>
                        <textarea name="return_remarks" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-primary" type="submit">Confirm Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.EQUIPMENT_ID = <?= (int)$equipmentId ?>;
</script>
<?php if (!$isPartial) {
    require_once __DIR__ . '/../includes/footer.php';
} ?>
