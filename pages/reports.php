<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

// Default date range: this month
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$tab = $_GET['tab'] ?? 'occupancy';

$occupancy = generate_occupancy_report($start_date, $end_date);
$revenue = generate_revenue_report($start_date, $end_date);
?>
<div class="container mt-4">
    <h2 class="mb-4">Reports</h2>
    <ul class="nav nav-tabs mb-3" id="reportTabs">
        <li class="nav-item">
            <a class="nav-link<?= $tab === 'occupancy' ? ' active' : '' ?>" href="?tab=occupancy">Occupancy</a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?= $tab === 'revenue' ? ' active' : '' ?>" href="?tab=revenue">Revenue</a>
        </li>
    </ul>
    <form class="row g-3 mb-4" method="get">
        <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
        <div class="col-auto">
            <label for="start_date" class="form-label">From</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
        </div>
        <div class="col-auto">
            <label for="end_date" class="form-label">To</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary">Apply</button>
        </div>
    </form>
    <?php if ($tab === 'occupancy'): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Occupancy Rate</h4>
                <canvas id="occupancyChart" height="80"></canvas>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Rooms Occupied</th>
                                <th>Total Rooms</th>
                                <th>Occupancy Rate (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($occupancy as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= htmlspecialchars($row['rooms_occupied']) ?></td>
                                <td><?= htmlspecialchars($row['total_rooms']) ?></td>
                                <td><?= round($row['occupancy_rate'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Revenue</h4>
                <canvas id="revenueChart" height="80"></canvas>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Revenue</th>
                                <th>Payments</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($revenue as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= format_currency($row['daily_revenue']) ?></td>
                                <td><?= htmlspecialchars($row['payment_count']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const occupancyData = <?= json_encode(array_map(fn($r) => (float) $r['occupancy_rate'], $occupancy)) ?>;
const occupancyLabels = <?= json_encode(array_map(fn($r) => $r['date'], $occupancy)) ?>;
const revenueData = <?= json_encode(array_map(fn($r) => (float) $r['daily_revenue'], $revenue)) ?>;
const revenueLabels = <?= json_encode(array_map(fn($r) => $r['date'], $revenue)) ?>;

if (document.getElementById('occupancyChart')) {
    new Chart(document.getElementById('occupancyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: occupancyLabels,
            datasets: [{
                label: 'Occupancy Rate (%)',
                data: occupancyData,
                borderColor: '#3949ab',
                backgroundColor: 'rgba(57,73,171,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, max: 100 } }
        }
    });
}
if (document.getElementById('revenueChart')) {
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue',
                data: revenueData,
                backgroundColor: '#1a237e',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}
</script>
<style>
.card { border-radius: 1rem; }
.card-title { font-weight: 700; }
.nav-tabs .nav-link.active { background: #1a237e; color: #fff; border: none; }
.nav-tabs .nav-link { color: #1a237e; font-weight: 500; }
.btn-primary { background: #1a237e; border: none; }
.btn-primary:hover { background: #3949ab; }
.table { background: #fff; }
</style> 