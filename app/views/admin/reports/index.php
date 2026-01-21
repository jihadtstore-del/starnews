<h3 class="mb-3">Issue Reports</h3>

<form method="get" action="index.php" class="row g-3 align-items-end mb-3">
    <input type="hidden" name="route" value="reports">
    <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($filters['date']); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Technician</label>
        <select name="technician_id" class="form-select">
            <option value="">All</option>
            <?php foreach ($technicians as $tech): ?>
                <option value="<?php echo (int) $tech['id']; ?>" <?php echo ($filters['technician_id'] === (int) $tech['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($tech['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-primary" type="submit">Filter</button>
        <a class="btn btn-outline-secondary" target="_blank" href="index.php?route=reports-print&date=<?php echo urlencode($filters['date']); ?>&technician_id=<?php echo urlencode((string) $filters['technician_id']); ?>">Print View</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Technician</th>
                <th>Equipment</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($issues)): ?>
                <tr>
                    <td colspan="5" class="text-center">কোনো রিপোর্ট পাওয়া যায়নি।</td>
                </tr>
            <?php else: ?>
                <?php foreach ($issues as $issue): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($issue['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($issue['technician_name']); ?></td>
                        <td><?php echo htmlspecialchars($issue['equipment_name']); ?></td>
                        <td><?php echo htmlspecialchars((string) $issue['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($issue['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
