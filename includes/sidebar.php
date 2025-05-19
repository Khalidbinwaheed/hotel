<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$user_role = $_SESSION['role'] ?? '';
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h1 class="logo">LuxeStay</h1>
        <button id="sidebar-toggle" class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="user-info">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-details">
            <h3><?php echo $_SESSION['name'] ?? 'User'; ?></h3>
            <p><?php echo ucfirst($_SESSION['role'] ?? 'Staff'); ?></p>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="index.php?page=dashboard" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-section">
                <span class="nav-section-title">Reservations</span>
            </li>
            
            <li>
                <a href="index.php?page=reservations" class="<?php echo $current_page == 'reservations' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>Reservations</span>
                </a>
            </li>
            
            <li>
                <a href="index.php?page=check_in" class="<?php echo $current_page == 'check_in' ? 'active' : ''; ?>">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Check-In</span>
                </a>
            </li>
            
            <li>
                <a href="index.php?page=check_out" class="<?php echo $current_page == 'check_out' ? 'active' : ''; ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Check-Out</span>
                </a>
            </li>
            
            <li class="nav-section">
                <span class="nav-section-title">Hotel Management</span>
            </li>
            
            <li>
                <a href="index.php?page=rooms" class="<?php echo $current_page == 'rooms' ? 'active' : ''; ?>">
                    <i class="fas fa-bed"></i>
                    <span>Rooms</span>
                </a>
            </li>
            
            <li>
                <a href="index.php?page=guests" class="<?php echo $current_page == 'guests' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Guests</span>
                </a>
            </li>
            
            <li>
                <a href="index.php?page=housekeeping" class="<?php echo $current_page == 'housekeeping' ? 'active' : ''; ?>">
                    <i class="fas fa-broom"></i>
                    <span>Housekeeping</span>
                </a>
            </li>
            
            <li>
                <a href="index.php?page=services" class="<?php echo $current_page == 'services' ? 'active' : ''; ?>">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Services</span>
                </a>
            </li>
            
            <?php if (in_array($user_role, ['admin', 'manager'])): ?>
            <li class="nav-section">
                <span class="nav-section-title">Administration</span>
            </li>
            
            <li>
                <a href="index.php?page=billing" class="<?php echo $current_page == 'billing' ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Billing</span>
                </a>
            </li>
            
            <li>
                <a href="index.php?page=reports" class="<?php echo $current_page == 'reports' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($user_role == 'admin'): ?>
            <li>
                <a href="index.php?page=users" class="<?php echo $current_page == 'users' ? 'active' : ''; ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>Users</span>
                </a>
            </li>
            
            <li>
                <a href="index.php?page=settings" class="<?php echo $current_page == 'settings' ? 'active' : ''; ?>">
                    <i class="fas fa-cogs"></i>
                    <span>Settings</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-power-off"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>