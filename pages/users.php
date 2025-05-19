<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$conn = Database::getConnection();
$sql = "SELECT * FROM users ORDER BY name";
$result = $conn->query($sql);
?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title mb-0">Users</h2>
                <a href="add_user.php" class="btn btn-primary">+ Add User</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars(ucfirst($row['role'])) ?></span></td>
                                <td>
                                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr><td colspan="6">No users found.</td></tr>
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
.badge.bg-secondary { background: #3949ab; }
.table { background: #fff; }
</style> 