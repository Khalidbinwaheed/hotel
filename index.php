<?php
session_start();
include 'config/database.php';
// include 'includes/functions.php'; // Removed to prevent redeclaration

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStay Hotel Management</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php include 'includes/sidebar.php'; ?>
        <?php endif; ?>
        
        <main class="content">
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php include 'includes/header.php'; ?>
            <?php endif; ?>
            
            <div class="page-content">
                <?php
                if (file_exists('pages/' . $page . '.php')) {
                    include 'pages/' . $page . '.php';
                } else {
                    include 'pages/404.php';
                }
                ?>
            </div>
        </main>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>