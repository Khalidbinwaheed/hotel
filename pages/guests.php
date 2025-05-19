<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$conn = Database::getConnection();
$sql = "SELECT * FROM guests ORDER BY last_name, first_name";
$result = $conn->query($sql);
?>
<div class="container mt-4">
    <h2>Guests</h2>
    <a href="add_guest.php" class="btn btn-primary mb-2">Add Guest</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>ID Type</th>
                <th>ID Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['id_type']) ?></td>
                    <td><?= htmlspecialchars($row['id_number']) ?></td>
                    <td>
                        <a href="edit_guest.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_guest.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this guest?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile;
        else: ?>
            <tr><td colspan="7">No guests found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div> 