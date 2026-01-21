<h3 class="mb-3">Issue History</h3>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Equipment</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($issues)): ?>
                <tr>
                    <td colspan="5" class="text-center">কোনো Issue পাওয়া যায়নি।</td>
                </tr>
            <?php else: ?>
                <?php foreach ($issues as $issue): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($issue['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($issue['equipment_name']); ?></td>
                        <td><?php echo htmlspecialchars((string) $issue['quantity']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($issue['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($issue['return_date'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
