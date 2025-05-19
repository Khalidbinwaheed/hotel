<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

// Simulate settings fetch (replace with DB fetch in real app)
$settings = [
    'hotel_name' => 'LuxeStay Hotel',
    'address' => '123 Main Street, City, Country',
    'phone' => '+1 234 567 890',
    'email' => 'info@luxestay.com',
];

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Here you would update settings in the DB
    $settings['hotel_name'] = $_POST['hotel_name'] ?? $settings['hotel_name'];
    $settings['address'] = $_POST['address'] ?? $settings['address'];
    $settings['phone'] = $_POST['phone'] ?? $settings['phone'];
    $settings['email'] = $_POST['email'] ?? $settings['email'];
    $success = 'Settings updated successfully!';
}
?>
<div class="container mt-4" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-4">Hotel Settings</h2>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="hotel_name" class="form-label">Hotel Name</label>
                    <input type="text" class="form-control" id="hotel_name" name="hotel_name" value="<?= htmlspecialchars($settings['hotel_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($settings['address']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($settings['phone']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($settings['email']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Save Settings</button>
            </form>
        </div>
    </div>
</div>
<style>
.card { border-radius: 1rem; }
.card-title { font-weight: 700; }
.form-label { font-weight: 500; }
.btn-primary { background: #1a237e; border: none; }
.btn-primary:hover { background: #3949ab; }
.alert { margin-top: 1rem; }
</style> 