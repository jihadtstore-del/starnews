<h3 class="mb-3">Daily Issue Entry</h3>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="index.php?route=issue-store" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issue_date" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Technician Name</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($technician['name'] ?? ''); ?>" readonly>
        <input type="hidden" name="technician_id" value="<?php echo (int) ($technician['id'] ?? 0); ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control" value="<?php echo htmlspecialchars($technician['department'] ?? ''); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Equipment</label>
        <select name="equipment_id" class="form-select" required>
            <option value="">Select</option>
            <?php foreach ($equipment as $item): ?>
                <option value="<?php echo (int) $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" class="form-control" min="1" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Issue Purpose</label>
        <input type="text" name="issue_purpose" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Return Date (Optional)</label>
        <input type="date" name="return_date" class="form-control">
    </div>
    <div class="col-12">
        <button class="btn btn-success" type="submit">Submit Issue</button>
    </div>
</form>
