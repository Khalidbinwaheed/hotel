<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$error = '';

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Authenticate user
    $user = authenticate_user($username, $password);
    
    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect to dashboard
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LuxeStay Hotel Management</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-wrapper">
            <div class="login-form-container">
                <div class="login-header">
                    <h1>LuxeStay</h1>
                    <p>Hotel Management System</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="login.php" class="login-form">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Username</label>
                        <input type="text" id="username" name="username" required autocomplete="username">
                    </div>
                    
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>
                </form>
                
                <div class="login-footer">
                    <p>Default login credentials:</p>
                    <p>Username: <strong>admin</strong> | Password: <strong>admin123</strong></p>
                </div>
            </div>
            
            <div class="login-image">
                <div class="hotel-info">
                    <h2>Welcome to LuxeStay</h2>
                    <p>Streamline your hotel operations with our comprehensive management system.</p>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Efficient reservation management</li>
                        <li><i class="fas fa-check-circle"></i> Guest profiles and preferences</li>
                        <li><i class="fas fa-check-circle"></i> Room management and housekeeping</li>
                        <li><i class="fas fa-check-circle"></i> Billing and invoicing</li>
                        <li><i class="fas fa-check-circle"></i> Reports and analytics</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>