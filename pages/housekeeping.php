<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

$conn = Database::getConnection();
$sql = "SELECT h.*, r.room_number, u.name as assigned_name FROM housekeeping h JOIN rooms r ON h.room_id = r.id LEFT JOIN users u ON h.assigned_to = u.id ORDER BY h.scheduled_date DESC";
$result = $conn->query($sql);
?>
<div class="container mt-4">
    <h2>Housekeeping Tasks</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Room</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Assigned To</th>
                <th>Scheduled Date</th>
                <th>Completed Date</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['room_number'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['notes'] ?></td>
                    <td><?= $row['assigned_name'] ?></td>
                    <td><?= $row['scheduled_date'] ?></td>
                    <td><?= $row['completed_date'] ?></td>
                </tr>
            <?php endwhile;
        else: ?>
            <tr><td colspan="7">No housekeeping tasks found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/header.php'; ?> 