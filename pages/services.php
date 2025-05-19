<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$conn = Database::getConnection();
$sql = "SELECT * FROM services ORDER BY name";
$result = $conn->query($sql);
?>
<div class="container mt-4">
    <h2>Services</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                </tr>
            <?php endwhile;
        else: ?>
            <tr><td colspan="5">No services found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div> 