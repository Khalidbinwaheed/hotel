<?php
// Default filters
$filters = [
    'status' => isset($_GET['status']) ? $_GET['status'] : '',
    'date_from' => isset($_GET['date_from']) ? $_GET['date_from'] : '',
    'date_to' => isset($_GET['date_to']) ? $_GET['date_to'] : '',
];

// Get reservations based on filters
$reservations = get_reservations($filters);

// Get room categories for filtering
$room_categories = get_room_categories();
?>

<div class="page-header">
    <h1>Reservations Management</h1>
    <div class="page-actions">
        <a href="index.php?page=new_reservation" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Reservation
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Search Reservations</h3>
        <button class="btn btn-sm btn-outline-primary" id="toggle-filters">
            <i class="fas fa-filter"></i> Filters
        </button>
    </div>
    <div class="card-body filters-container" id="filters-container">
        <form action="index.php" method="GET" class="filters-form">
            <input type="hidden" name="page" value="reservations">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="confirmed" <?php echo $filters['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="checked_in" <?php echo $filters['status'] == 'checked_in' ? 'selected' : ''; ?>>Checked In</option>
                        <option value="checked_out" <?php echo $filters['status'] == 'checked_out' ? 'selected' : ''; ?>>Checked Out</option>
                        <option value="cancelled" <?php echo $filters['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date_from">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $filters['date_from']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="date_to">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo $filters['date_to']; ?>">
                </div>
                
                <div class="form-group form-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="index.php?page=reservations" class="btn btn-outline-secondary">Clear Filters</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Reservations List</h3>
        <div class="card-tools">
            <input type="text" id="reservation-search" class="form-control form-control-sm" placeholder="Search reservations...">
        </div>
    </div>
    <div class="card-body">
        <?php if (count($reservations) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Nights</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td>
                            <a href="index.php?page=reservation_detail&id=<?php echo $reservation['id']; ?>">
                                #<?php echo str_pad($reservation['id'], 5, '0', STR_PAD_LEFT); ?>
                            </a>
                        </td>
                        <td>
                            <a href="index.php?page=guest_detail&id=<?php echo $reservation['guest_id']; ?>">
                                <?php echo $reservation['first_name'] . ' ' . $reservation['last_name']; ?>
                            </a>
                        </td>
                        <td><?php echo $reservation['room_number']; ?> (<?php echo $reservation['room_category']; ?>)</td>
                        <td><?php echo format_date($reservation['check_in']); ?></td>
                        <td><?php echo format_date($reservation['check_out']); ?></td>
                        <td><?php echo get_date_difference($reservation['check_in'], $reservation['check_out']); ?></td>
                        <td>
                            <?php if ($reservation['status'] == 'confirmed'): ?>
                                <span class="badge bg-success">Confirmed</span>
                            <?php elseif ($reservation['status'] == 'checked_in'): ?>
                                <span class="badge bg-primary">Checked In</span>
                            <?php elseif ($reservation['status'] == 'checked_out'): ?>
                                <span class="badge bg-secondary">Checked Out</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="index.php?page=reservation_detail&id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <?php if ($reservation['status'] == 'confirmed'): ?>
                                <a href="index.php?page=check_in_process&id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-sign-in-alt"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($reservation['status'] == 'checked_in'): ?>
                                <a href="index.php?page=check_out_process&id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($reservation['status'] == 'confirmed'): ?>
                                <a href="index.php?page=reservation_edit&id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h4>No Reservations Found</h4>
            <p>There are no reservations matching your search criteria.</p>
            <a href="index.php?page=new_reservation" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Reservation
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filters
    const toggleFilters = document.getElementById('toggle-filters');
    const filtersContainer = document.getElementById('filters-container');
    
    if (toggleFilters && filtersContainer) {
        toggleFilters.addEventListener('click', function() {
            filtersContainer.classList.toggle('active');
            this.innerHTML = filtersContainer.classList.contains('active') 
                ? '<i class="fas fa-times"></i> Close' 
                : '<i class="fas fa-filter"></i> Filters';
        });
    }
    
    // Check if any filters are active, then show the filters container
    const hasActiveFilters = <?php echo (!empty($filters['status']) || !empty($filters['date_from']) || !empty($filters['date_to'])) ? 'true' : 'false'; ?>;
    
    if (hasActiveFilters && filtersContainer) {
        filtersContainer.classList.add('active');
        if (toggleFilters) {
            toggleFilters.innerHTML = '<i class="fas fa-times"></i> Close';
        }
    }
    
    // Reservation search functionality
    const searchInput = document.getElementById('reservation-search');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>