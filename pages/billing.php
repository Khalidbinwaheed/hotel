<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$conn = Database::getConnection();
$sql = "SELECT * FROM payments ORDER BY payment_date DESC";
$result = $conn->query($sql);

// Key stats
$total_revenue = 0;
$completed = $pending = 0;
$res_stats = $conn->query("SELECT SUM(amount) as total, SUM(status='completed') as completed, SUM(status='pending') as pending FROM payments");
if ($res_stats && $row = $res_stats->fetch_assoc()) {
    $total_revenue = $row['total'] ?: 0;
    $completed = $row['completed'] ?: 0;
    $pending = $row['pending'] ?: 0;
}
?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text display-6"><?= format_currency($total_revenue) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Completed Payments</h5>
                    <p class="card-text display-6"><?= htmlspecialchars($completed) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Payments</h5>
                    <p class="card-text display-6"><?= htmlspecialchars($pending) ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title mb-0">Billing / Payments</h2>
                <a href="add_payment.php" class="btn btn-primary">+ Add Payment</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Reservation ID</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['reservation_id']) ?></td>
                                <td><?= format_currency($row['amount']) ?></td>
                                <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $row['payment_method']))) ?></td>
                                <td><span class="badge bg-<?= $row['status'] === 'completed' ? 'success' : ($row['status'] === 'pending' ? 'warning' : 'secondary') ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></span></td>
                                <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                                <td>
                                    <a href="edit_payment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete_payment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr><td colspan="8">No payments found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
.card { border-radius: 1rem; }
.card-title { font-weight: 700; }
.btn-primary { background: #1a237e; border: none; }
.btn-primary:hover { background: #3949ab; }
.badge.bg-success { background: #388e3c; }
.badge.bg-warning { background: #fbc02d; color: #222; }
.badge.bg-secondary { background: #757575; }
.table { background: #fff; }
</style> 