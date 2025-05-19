

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Authentication functions
require_once __DIR__ . '/../config/database.php';

function authenticate_user($username, $password) {
    $conn = Database::getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    return false;
}

function get_user_by_id($user_id) {
    $conn = Database::getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows == 1 ? $result->fetch_assoc() : false;
}

function get_all_users() {
    $conn = Database::getConnection();
    
    $sql = "SELECT * FROM users ORDER BY name";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn));
        return [];
    }
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    return $users;
}

function create_user($username, $password, $name, $email, $role) {
    $conn = Database::getConnection();
    
    $username = mysqli_real_escape_string($conn, $username);
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $role = mysqli_real_escape_string($conn, $role);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, password, name, email, role) 
            VALUES ('$username', '$hashed_password', '$name', '$email', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        return mysqli_insert_id($conn);
    }
    
    return false;
}

// Room functions
function get_room_categories() {
    $conn = Database::getConnection();
    
    $sql = "SELECT * FROM room_categories ORDER BY name";
    $result = mysqli_query($conn, $sql);
    
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    
    return $categories;
}

function get_rooms($filters = []) {
    $conn = Database::getConnection();
    
    $where_clauses = [];
    
    if (isset($filters['category_id']) && $filters['category_id']) {
        $category_id = mysqli_real_escape_string($conn, $filters['category_id']);
        $where_clauses[] = "r.category_id = '$category_id'";
    }
    
    if (isset($filters['status']) && $filters['status']) {
        $status = mysqli_real_escape_string($conn, $filters['status']);
        $where_clauses[] = "r.status = '$status'";
    }
    
    if (isset($filters['floor']) && $filters['floor']) {
        $floor = mysqli_real_escape_string($conn, $filters['floor']);
        $where_clauses[] = "r.floor = '$floor'";
    }
    
    $where_clause = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
    
    $sql = "SELECT r.*, c.name as category_name, c.base_price 
            FROM rooms r 
            JOIN room_categories c ON r.category_id = c.id 
            $where_clause 
            ORDER BY r.room_number";
    
    $result = mysqli_query($conn, $sql);
    
    $rooms = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
    
    return $rooms;
}

function get_available_rooms($check_in, $check_out, $category_id = null) {
    $conn = Database::getConnection();
    
    $check_in = mysqli_real_escape_string($conn, $check_in);
    $check_out = mysqli_real_escape_string($conn, $check_out);
    
    $category_clause = "";
    if ($category_id) {
        $category_id = mysqli_real_escape_string($conn, $category_id);
        $category_clause = "AND r.category_id = '$category_id'";
    }
    
    $sql = "SELECT r.*, c.name as category_name, c.base_price 
            FROM rooms r 
            JOIN room_categories c ON r.category_id = c.id 
            WHERE r.id NOT IN (
                SELECT rv.room_id 
                FROM reservations rv 
                WHERE (rv.check_in <= '$check_out' AND rv.check_out >= '$check_in')
                AND rv.status IN ('confirmed', 'checked_in')
            )
            AND r.status = 'available'
            $category_clause
            ORDER BY r.room_number";
    
    $result = mysqli_query($conn, $sql);
    
    $rooms = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
    
    return $rooms;
}

// Reservation functions
function create_reservation($guest_id, $room_id, $check_in, $check_out, $adults, $children, $status, $special_requests, $created_by) {
    $conn = Database::getConnection();
    
    $guest_id = mysqli_real_escape_string($conn, $guest_id);
    $room_id = mysqli_real_escape_string($conn, $room_id);
    $check_in = mysqli_real_escape_string($conn, $check_in);
    $check_out = mysqli_real_escape_string($conn, $check_out);
    $adults = mysqli_real_escape_string($conn, $adults);
    $children = mysqli_real_escape_string($conn, $children);
    $status = mysqli_real_escape_string($conn, $status);
    $special_requests = mysqli_real_escape_string($conn, $special_requests);
    $created_by = mysqli_real_escape_string($conn, $created_by);
    
    $sql = "INSERT INTO reservations (guest_id, room_id, check_in, check_out, adults, children, status, special_requests, created_by) 
            VALUES ('$guest_id', '$room_id', '$check_in', '$check_out', '$adults', '$children', '$status', '$special_requests', '$created_by')";
    
    if (mysqli_query($conn, $sql)) {
        return mysqli_insert_id($conn);
    }
    
    return false;
}

function get_reservations($filters = []) {
    $conn = Database::getConnection();
    
    $where_clauses = [];
    
    if (isset($filters['guest_id']) && $filters['guest_id']) {
        $guest_id = mysqli_real_escape_string($conn, $filters['guest_id']);
        $where_clauses[] = "res.guest_id = '$guest_id'";
    }
    
    if (isset($filters['room_id']) && $filters['room_id']) {
        $room_id = mysqli_real_escape_string($conn, $filters['room_id']);
        $where_clauses[] = "res.room_id = '$room_id'";
    }
    
    if (isset($filters['status']) && $filters['status']) {
        $status = mysqli_real_escape_string($conn, $filters['status']);
        $where_clauses[] = "res.status = '$status'";
    }
    
    if (isset($filters['date_from']) && $filters['date_from']) {
        $date_from = mysqli_real_escape_string($conn, $filters['date_from']);
        $where_clauses[] = "res.check_in >= '$date_from'";
    }
    
    if (isset($filters['date_to']) && $filters['date_to']) {
        $date_to = mysqli_real_escape_string($conn, $filters['date_to']);
        $where_clauses[] = "res.check_out <= '$date_to'";
    }
    
    if (isset($filters['today_check_in']) && $filters['today_check_in']) {
        $today = date('Y-m-d');
        $where_clauses[] = "res.check_in = '$today'";
    }
    
    if (isset($filters['today_check_out']) && $filters['today_check_out']) {
        $today = date('Y-m-d');
        $where_clauses[] = "res.check_out = '$today'";
    }
    
    $where_clause = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
    
    $sql = "SELECT res.*, 
            g.first_name, g.last_name, g.phone, g.email,
            r.room_number, rc.name as room_category,
            u.name as created_by_name
            FROM reservations res
            JOIN guests g ON res.guest_id = g.id
            JOIN rooms r ON res.room_id = r.id
            JOIN room_categories rc ON r.category_id = rc.id
            LEFT JOIN users u ON res.created_by = u.id
            $where_clause
            ORDER BY res.check_in DESC";
    
    $result = mysqli_query($conn, $sql);
    
    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
    
    return $reservations;
}

// Guest functions
function create_guest($first_name, $last_name, $email, $phone, $address, $id_type, $id_number, $notes) {
    $conn = Database::getConnection();
    
    $first_name = mysqli_real_escape_string($conn, $first_name);
    $last_name = mysqli_real_escape_string($conn, $last_name);
    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);
    $address = mysqli_real_escape_string($conn, $address);
    $id_type = mysqli_real_escape_string($conn, $id_type);
    $id_number = mysqli_real_escape_string($conn, $id_number);
    $notes = mysqli_real_escape_string($conn, $notes);
    
    $sql = "INSERT INTO guests (first_name, last_name, email, phone, address, id_type, id_number, notes) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$id_type', '$id_number', '$notes')";
    
    if (mysqli_query($conn, $sql)) {
        return mysqli_insert_id($conn);
    }
    
    return false;
}

function get_guests($search = '') {
    $conn = Database::getConnection();
    
    $where_clause = "";
    if ($search) {
        $search = mysqli_real_escape_string($conn, $search);
        $where_clause = "WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR id_number LIKE '%$search%'";
    }
    
    $sql = "SELECT * FROM guests $where_clause ORDER BY last_name, first_name";
    $result = mysqli_query($conn, $sql);
    
    $guests = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $guests[] = $row;
    }
    
    return $guests;
}

// Dashboard stats
function get_dashboard_stats() {
    $conn = Database::getConnection();
    
    $today = date('Y-m-d');
    
    // Available rooms
    $sql_available = "SELECT COUNT(*) as count FROM rooms WHERE status = 'available'";
    $result_available = mysqli_query($conn, $sql_available);
    $available_rooms = mysqli_fetch_assoc($result_available)['count'];
    
    // Occupied rooms
    $sql_occupied = "SELECT COUNT(*) as count FROM rooms WHERE status = 'occupied'";
    $result_occupied = mysqli_query($conn, $sql_occupied);
    $occupied_rooms = mysqli_fetch_assoc($result_occupied)['count'];
    
    // Today's check-ins
    $sql_checkin = "SELECT COUNT(*) as count FROM reservations WHERE check_in = '$today' AND status = 'confirmed'";
    $result_checkin = mysqli_query($conn, $sql_checkin);
    $today_checkins = mysqli_fetch_assoc($result_checkin)['count'];
    
    // Today's check-outs
    $sql_checkout = "SELECT COUNT(*) as count FROM reservations WHERE check_out = '$today' AND status = 'checked_in'";
    $result_checkout = mysqli_query($conn, $sql_checkout);
    $today_checkouts = mysqli_fetch_assoc($result_checkout)['count'];
    
    // This month's revenue
    $first_day_month = date('Y-m-01');
    $last_day_month = date('Y-m-t');
    
    $sql_revenue = "SELECT SUM(amount) as total FROM payments WHERE payment_date BETWEEN '$first_day_month' AND '$last_day_month' AND status = 'completed'";
    $result_revenue = mysqli_query($conn, $sql_revenue);
    $monthly_revenue = mysqli_fetch_assoc($result_revenue)['total'] ?: 0;
    
    // Housekeeping pending
    $sql_housekeeping = "SELECT COUNT(*) as count FROM housekeeping WHERE status = 'pending' AND scheduled_date <= '$today'";
    $result_housekeeping = mysqli_query($conn, $sql_housekeeping);
    $pending_housekeeping = mysqli_fetch_assoc($result_housekeeping)['count'];
    
    return [
        'available_rooms' => $available_rooms,
        'occupied_rooms' => $occupied_rooms,
        'today_checkins' => $today_checkins,
        'today_checkouts' => $today_checkouts,
        'monthly_revenue' => $monthly_revenue,
        'pending_housekeeping' => $pending_housekeeping
    ];
}

// Generate reports
function generate_occupancy_report($start_date, $end_date) {
    $conn = Database::getConnection();
    
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);
    
    $sql = "SELECT 
            DATE(res.check_in) as date,
            COUNT(DISTINCT res.room_id) as rooms_occupied,
            (SELECT COUNT(*) FROM rooms) as total_rooms,
            (COUNT(DISTINCT res.room_id) / (SELECT COUNT(*) FROM rooms)) * 100 as occupancy_rate
            FROM reservations res
            WHERE res.check_in <= '$end_date' AND res.check_out >= '$start_date'
            AND res.status IN ('confirmed', 'checked_in')
            GROUP BY DATE(res.check_in)
            ORDER BY date ASC";
    
    $result = mysqli_query($conn, $sql);
    
    $report = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $report[] = $row;
    }
    
    return $report;
}

function generate_revenue_report($start_date, $end_date) {
    $conn = Database::getConnection();
    
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);
    
    $sql = "SELECT 
            DATE(p.payment_date) as date,
            SUM(p.amount) as daily_revenue,
            COUNT(p.id) as payment_count
            FROM payments p
            WHERE p.payment_date BETWEEN '$start_date' AND '$end_date'
            AND p.status = 'completed'
            GROUP BY DATE(p.payment_date)
            ORDER BY date ASC";
    
    $result = mysqli_query($conn, $sql);
    
    $report = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $report[] = $row;
    }
    
    return $report;
}

// Format functions
function format_currency($amount) {
    return '$' . number_format($amount, 2);
}

function format_date($date) {
    return date('M d, Y', strtotime($date));
}

function format_datetime($datetime) {
    return date('M d, Y h:i A', strtotime($datetime));
}

function get_date_difference($date1, $date2) {
    $datetime1 = new DateTime($date1);
    $datetime2 = new DateTime($date2);
    $interval = $datetime1->diff($datetime2);
    return $interval->days;
}

// Utility functions
function generate_invoice_number() {
    return 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
}

function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function get_current_user_role() {
    if (isset($_SESSION['user_id'])) {
        $user = get_user_by_id($_SESSION['user_id']);
        return $user ? $user['role'] : null;
    }
    return null;
}

function has_permission($required_role) {
    $current_role = get_current_user_role();
    
    if ($current_role == 'admin') {
        return true;
    }
    
    if ($required_role == 'admin' && $current_role != 'admin') {
        return false;
    }
    
    if ($required_role == 'manager' && !in_array($current_role, ['admin', 'manager'])) {
        return false;
    }
    
    if ($required_role == 'receptionist' && !in_array($current_role, ['admin', 'manager', 'receptionist'])) {
        return false;
    }
    
    return true;
}

// Utility function to ensure the default admin user exists
function ensure_default_admin_user() {
    $conn = Database::getConnection();
    $username = 'admin';
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $name = 'System Administrator';
    $email = 'admin@hotel.com';
    $role = 'admin';

    // Check if admin user exists
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Create new admin user
        $insert = $conn->prepare("INSERT INTO users (username, password, name, email, role) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("sssss", $username, $hashed_password, $name, $email, $role);
        $insert->execute();
        $insert->close();
    } else {
        $user = $result->fetch_assoc();
        // Update admin user if password is not hashed or is incorrect
        if (!password_verify($password, $user['password'])) {
            $update = $conn->prepare("UPDATE users SET password = ?, name = ?, email = ?, role = ? WHERE username = ?");
            $update->bind_param("sssss", $hashed_password, $name, $email, $role, $username);
            $update->execute();
            $update->close();
        }
    }
    $stmt->close();
}

// Ensure the default admin user exists on every load
ensure_default_admin_user();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}