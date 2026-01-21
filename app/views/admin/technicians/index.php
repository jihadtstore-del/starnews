<h3 class="mb-3">Technician Management</h3>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="post" action="index.php?route=technician-store" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">User ID (optional)</label>
                <input type="number" name="user_id" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Department</label>
                <input type="text" name="department" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="col-md-1">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Add Technician</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($technicians as $tech): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tech['name']); ?></td>
                    <td><?php echo htmlspecialchars($tech['department']); ?></td>
                    <td><?php echo htmlspecialchars($tech['phone']); ?></td>
                    <td><?php echo htmlspecialchars($tech['status']); ?></td>
                    <td>
                        <form method="post" action="index.php?route=technician-delete" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="id" value="<?php echo (int) $tech['id']; ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
