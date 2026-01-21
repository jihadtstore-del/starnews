<h3 class="mb-3">Daily Issue Approval</h3>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Technician</th>
                <th>Equipment</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Return Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($issues as $issue): ?>
                <tr>
                    <td><?php echo htmlspecialchars($issue['issue_date']); ?></td>
                    <td><?php echo htmlspecialchars($issue['technician_name']); ?></td>
                    <td><?php echo htmlspecialchars($issue['equipment_name']); ?></td>
                    <td><?php echo htmlspecialchars((string) $issue['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($issue['status']); ?></td>
                    <td><?php echo htmlspecialchars($issue['return_date'] ?? '-'); ?></td>
                    <td class="d-flex gap-2">
                        <form method="post" action="index.php?route=issue-approve">
                            <input type="hidden" name="id" value="<?php echo (int) $issue['id']; ?>">
                            <button class="btn btn-sm btn-success" type="submit">Approve</button>
                        </form>
                        <form method="post" action="index.php?route=issue-reject">
                            <input type="hidden" name="id" value="<?php echo (int) $issue['id']; ?>">
                            <button class="btn btn-sm btn-warning" type="submit">Reject</button>
                        </form>
                        <form method="post" action="index.php?route=issue-return" class="d-flex gap-2">
                            <input type="hidden" name="id" value="<?php echo (int) $issue['id']; ?>">
                            <input type="date" name="return_date" class="form-control form-control-sm" required>
                            <button class="btn btn-sm btn-primary" type="submit">Returned</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
