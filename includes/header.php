<?php
require_once __DIR__ . '/functions.php';
$user = isset($_SESSION['user_id']) ? get_user_by_id($_SESSION['user_id']) : null;
?>
<header class="header">
    <div class="header-left">
        <h2 class="page-title">
            <?php
            $page_title = 'Dashboard';
            if (isset($_GET['page'])) {
                $page_title = ucfirst(str_replace('_', ' ', $_GET['page']));
            }
            echo $page_title;
            ?>
        </h2>
        <nav class="breadcrumb">
            <a href="index.php">Home</a> / 
            <?php
            if (isset($_GET['page']) && $_GET['page'] != 'dashboard') {
                echo '<a href="index.php?page=' . $_GET['page'] . '">' . $page_title . '</a>';
            } else {
                echo 'Dashboard';
            }
            ?>
        </nav>
    </div>
    
    <div class="header-right">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search..." id="global-search">
        </div>
        
        <div class="notifications">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </button>
            <div class="notification-dropdown">
                <div class="notification-header">
                    <h3>Notifications</h3>
                    <a href="#">Mark all as read</a>
                </div>
                <div class="notification-list">
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">New reservation for Room 101</p>
                            <p class="notification-time">15 minutes ago</p>
                        </div>
                    </a>
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">Guest check-in: John Smith</p>
                            <p class="notification-time">1 hour ago</p>
                        </div>
                    </a>
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">Room 205 maintenance request</p>
                            <p class="notification-time">3 hours ago</p>
                        </div>
                    </a>
                </div>
                <div class="notification-footer">
                    <a href="#">View all notifications</a>
                </div>
            </div>
        </div>
        
        <div class="user-dropdown">
            <button class="user-dropdown-btn">
                <div class="user-avatar small">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="user-name"><?php echo $user ? $user['name'] : 'User'; ?></span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="user-dropdown-menu">
                <a href="index.php?page=profile">
                    <i class="fas fa-user"></i>
                    My Profile
                </a>
                <a href="index.php?page=settings">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>