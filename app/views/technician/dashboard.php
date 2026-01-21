<h3 class="mb-3">Technician Dashboard</h3>
<div class="card mb-4">
    <div class="card-body">
        <p class="mb-0">আপনার সাম্প্রতিক Issue তালিকা নিচে দেখুন।</p>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
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
                    <td colspan="5" class="text-center">এখনও কোনো Issue নেই।</td>
                </tr>
            <?php else: ?>
                <?php foreach ($issues as $issue): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($issue['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($issue['equipment_name']); ?></td>
                        <td><?php echo htmlspecialchars((string) $issue['quantity']); ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($issue['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($issue['return_date'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
