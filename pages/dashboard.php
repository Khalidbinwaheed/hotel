<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/functions.php';

// Get dashboard statistics
$stats = get_dashboard_stats();

// Get recent reservations
$recent_reservations = get_reservations(['limit' => 5]);

// Get upcoming check-ins
$today_checkins = get_reservations(['today_check_in' => true]);

// Get upcoming check-outs
$today_checkouts = get_reservations(['today_check_out' => true]);
?>

<div class="container mt-4">
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Available Rooms</h5>
                    <p class="card-text display-4"><?= htmlspecialchars($stats['available_rooms']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Occupied Rooms</h5>
                    <p class="card-text display-4"><?= htmlspecialchars($stats['occupied_rooms']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Guests</h5>
                    <p class="card-text display-4"><?php $conn = Database::getConnection(); $res = $conn->query('SELECT COUNT(*) as cnt FROM guests'); $row = $res->fetch_assoc(); echo htmlspecialchars($row['cnt']); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Today's Check-Ins</h5>
                    <p class="card-text display-4"><?= htmlspecialchars($stats['today_checkins']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Today's Check-Outs</h5>
                    <p class="card-text display-4"><?= htmlspecialchars($stats['today_checkouts']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">This Month's Revenue</h5>
                    <p class="card-text display-4"><?= format_currency($stats['monthly_revenue']) ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Housekeeping</h5>
                    <p class="card-text display-4"><?= htmlspecialchars($stats['pending_housekeeping']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard">
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-hotel"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">Available Rooms</h3>
                <p class="stat-value"><?php echo $stats['available_rooms']; ?></p>
                <p class="stat-description">Ready to book</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-bed"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">Occupied Rooms</h3>
                <p class="stat-value"><?php echo $stats['occupied_rooms']; ?></p>
                <p class="stat-description">Currently in use</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">Today's Check-ins</h3>
                <p class="stat-value"><?php echo $stats['today_checkins']; ?></p>
                <p class="stat-description">Arriving today</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">Today's Check-outs</h3>
                <p class="stat-value"><?php echo $stats['today_checkouts']; ?></p>
                <p class="stat-description">Departing today</p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-row">
        <div class="dashboard-col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Today's Check-ins</h3>
                    <a href="index.php?page=check_in" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($today_checkins) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($today_checkins as $reservation): ?>
                                <tr>
                                    <td>
                                        <?php echo $reservation['first_name'] . ' ' . $reservation['last_name']; ?>
                                    </td>
                                    <td><?php echo $reservation['room_number']; ?></td>
                                    <td><?php echo format_date($reservation['check_in']); ?></td>
                                    <td>
                                        <span class="badge bg-info">Arriving</span>
                                    </td>
                                    <td>
                                        <a href="index.php?page=reservation_detail&id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h4>No Check-ins Today</h4>
                        <p>There are no guests scheduled to check in today.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Room Status Overview</h3>
                </div>
                <div class="card-body">
                    <div class="room-status-chart">
                        <canvas id="roomStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Today's Check-outs</h3>
                    <a href="index.php?page=check_out" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($today_checkouts) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Room</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($today_checkouts as $reservation): ?>
                                <tr>
                                    <td>
                                        <?php echo $reservation['first_name'] . ' ' . $reservation['last_name']; ?>
                                    </td>
                                    <td><?php echo $reservation['room_number']; ?></td>
                                    <td><?php echo format_date($reservation['check_out']); ?></td>
                                    <td>
                                        <span class="badge bg-warning">Departing</span>
                                    </td>
                                    <td>
                                        <a href="index.php?page=reservation_detail&id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h4>No Check-outs Today</h4>
                        <p>There are no guests scheduled to check out today.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Reservations</h3>
                    <a href="index.php?page=reservations" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($recent_reservations) > 0): ?>
                    <div class="recent-reservations">
                        <?php foreach ($recent_reservations as $reservation): ?>
                        <div class="reservation-item">
                            <div class="reservation-guest">
                                <div class="guest-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="guest-info">
                                    <h4><?php echo $reservation['first_name'] . ' ' . $reservation['last_name']; ?></h4>
                                    <p>
                                        <i class="fas fa-phone"></i> <?php echo $reservation['phone']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="reservation-details">
                                <div class="detail-item">
                                    <span class="detail-label">Room</span>
                                    <span class="detail-value"><?php echo $reservation['room_number']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Check-in</span>
                                    <span class="detail-value"><?php echo format_date($reservation['check_in']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Check-out</span>
                                    <span class="detail-value"><?php echo format_date($reservation['check_out']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status</span>
                                    <span class="detail-value">
                                        <?php if ($reservation['status'] == 'confirmed'): ?>
                                            <span class="badge bg-success">Confirmed</span>
                                        <?php elseif ($reservation['status'] == 'checked_in'): ?>
                                            <span class="badge bg-primary">Checked In</span>
                                        <?php elseif ($reservation['status'] == 'checked_out'): ?>
                                            <span class="badge bg-secondary">Checked Out</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="reservation-actions">
                                <a href="index.php?page=reservation_detail&id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4>No Recent Reservations</h4>
                        <p>There are no recent reservations in the system.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Room Status Chart
    const roomStatusChart = document.getElementById('roomStatusChart');
    if (roomStatusChart) {
        new Chart(roomStatusChart, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Occupied', 'Maintenance', 'Reserved'],
                datasets: [{
                    data: [<?php echo $stats['available_rooms']; ?>, <?php echo $stats['occupied_rooms']; ?>, 2, 3],
                    backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f39c12'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initialize dropdown functionality
    const dropdownToggles = document.querySelectorAll('.user-dropdown-btn, .notification-btn');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('active');
            
            // Close other dropdowns
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    otherToggle.nextElementSibling.classList.remove('active');
                }
            });
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.user-dropdown-menu, .notification-dropdown').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });
    
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.app-container').classList.toggle('sidebar-collapsed');
        });
    }
});
</script>