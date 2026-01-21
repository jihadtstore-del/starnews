<h3 class="mb-3">Equipment Management</h3>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="post" action="index.php?route=equipment-store" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option>Camera</option>
                    <option>Microphone</option>
                    <option>Tripod</option>
                    <option>Light</option>
                    <option>Memory Card</option>
                    <option>Battery</option>
                    <option>Others</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Add Equipment</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipments as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                    <td><?php echo htmlspecialchars((string) $item['stock']); ?></td>
                    <td><?php echo htmlspecialchars($item['status']); ?></td>
                    <td>
                        <form method="post" action="index.php?route=equipment-delete" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
