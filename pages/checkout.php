<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$conn = Database::getConnection();
$today = date('Y-m-d');
$search = $_GET['search'] ?? '';
$success = $_GET['success'] ?? '';

$where = "res.status = 'checked_in' AND res.check_out <= '$today'";
if ($search) {
    $search_esc = $conn->real_escape_string($search);
    $where .= " AND (g.first_name LIKE '%$search_esc%' OR g.last_name LIKE '%$search_esc%' OR r.room_number LIKE '%$search_esc%')";
}
$sql = "SELECT res.id, g.first_name, g.last_name, r.room_number, res.check_in, res.check_out, res.status FROM reservations res JOIN guests g ON res.guest_id = g.id JOIN rooms r ON res.room_id = r.id WHERE $where ORDER BY res.check_out ASC";
$result = $conn->query($sql);
?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title mb-0">Check-Out</h2>
                <form class="d-flex" method="get" style="gap: 0.5rem;">
                    <input type="text" class="form-control" name="search" placeholder="Search guest or room..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
            <?php if ($success): ?>
                <div class="alert alert-success mb-3">Guest checked out successfully!</div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reservation ID</th>
                            <th>Guest Name</th>
                            <th>Room</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['room_number']) ?></td>
                                <td><?= htmlspecialchars($row['check_in']) ?></td>
                                <td><?= htmlspecialchars($row['check_out']) ?></td>
                                <td><span class="badge bg-success">Checked In</span></td>
                                <td>
                                    <a href="process_checkout.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Check-Out</a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr><td colspan="7">No reservations available for check-out.</td></tr>
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
.btn-danger { background: #d32f2f; border: none; }
.btn-danger:hover { background: #b71c1c; }
.badge.bg-success { background: #388e3c; }
.table { background: #fff; }
</style> 