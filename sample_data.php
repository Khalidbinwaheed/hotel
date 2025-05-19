<?php
require_once 'includes/functions.php';
$conn = Database::getConnection();

function table_empty($conn, $table) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM $table");
    $row = $result->fetch_assoc();
    return $row['cnt'] == 0;
}

// Rooms
if (table_empty($conn, 'rooms')) {
    $conn->query("INSERT INTO rooms (room_number, category_id, status, floor, description) VALUES
        ('101', 1, 'available', '1', 'Standard room'),
        ('102', 1, 'occupied', '1', 'Standard room'),
        ('201', 2, 'available', '2', 'Deluxe room'),
        ('202', 2, 'maintenance', '2', 'Deluxe room'),
        ('301', 3, 'available', '3', 'Suite room')");
    echo "Rooms: Sample data inserted.<br>";
} else {
    echo "Rooms: Data already exists.<br>";
}

// Guests
if (table_empty($conn, 'guests')) {
    $conn->query("INSERT INTO guests (first_name, last_name, email, phone, address, id_type, id_number, notes) VALUES
        ('John', 'Doe', 'john@example.com', '1234567890', '123 Main St', 'Passport', 'A1234567', ''),
        ('Jane', 'Smith', 'jane@example.com', '0987654321', '456 Elm St', 'ID Card', 'B7654321', 'VIP'),
        ('Alice', 'Brown', 'alice@example.com', '5551234567', '789 Oak St', 'Driver License', 'C9876543', '')");
    echo "Guests: Sample data inserted.<br>";
} else {
    echo "Guests: Data already exists.<br>";
}

// Reservations
if (table_empty($conn, 'reservations')) {
    $conn->query("INSERT INTO reservations (guest_id, room_id, check_in, check_out, adults, children, status, special_requests, created_by) VALUES
        (1, 2, '2024-06-01', '2024-06-05', 2, 0, 'checked_in', '', 1),
        (2, 3, '2024-06-10', '2024-06-12', 1, 1, 'confirmed', 'Late check-in', 1),
        (3, 5, '2024-06-15', '2024-06-20', 2, 2, 'confirmed', '', 1)");
    echo "Reservations: Sample data inserted.<br>";
} else {
    echo "Reservations: Data already exists.<br>";
}

// Payments
if (table_empty($conn, 'payments')) {
    $conn->query("INSERT INTO payments (reservation_id, amount, payment_method, status, transaction_id, notes, created_by) VALUES
        (1, 400.00, 'credit_card', 'completed', 'TXN1001', '', 1),
        (2, 300.00, 'cash', 'completed', 'TXN1002', '', 1),
        (3, 1250.00, 'online', 'pending', 'TXN1003', '', 1)");
    echo "Payments: Sample data inserted.<br>";
} else {
    echo "Payments: Data already exists.<br>";
}

// Services
if (table_empty($conn, 'services')) {
    $conn->query("INSERT INTO services (name, description, price, category) VALUES
        ('Breakfast', 'Buffet breakfast', 20.00, 'food'),
        ('Spa', 'Full body massage', 100.00, 'spa'),
        ('Laundry', 'Clothes washing', 10.00, 'laundry'),
        ('Airport Pickup', 'Transport from airport', 50.00, 'transport')");
    echo "Services: Sample data inserted.<br>";
} else {
    echo "Services: Data already exists.<br>";
}

// Service Orders
if (table_empty($conn, 'service_orders')) {
    $conn->query("INSERT INTO service_orders (reservation_id, service_id, quantity, status, notes, created_by) VALUES
        (1, 1, 2, 'delivered', '', 1),
        (2, 2, 1, 'pending', '', 1),
        (3, 4, 1, 'pending', 'VIP guest', 1)");
    echo "Service Orders: Sample data inserted.<br>";
} else {
    echo "Service Orders: Data already exists.<br>";
}

// Housekeeping
if (table_empty($conn, 'housekeeping')) {
    $conn->query("INSERT INTO housekeeping (room_id, status, notes, assigned_to, scheduled_date, completed_date) VALUES
        (1, 'pending', 'Clean after checkout', 1, '2024-06-06', NULL),
        (2, 'in_progress', 'Deep cleaning', 1, '2024-06-07', NULL),
        (4, 'completed', 'Maintenance done', 1, '2024-06-01', '2024-06-02 10:00:00')");
    echo "Housekeeping: Sample data inserted.<br>";
} else {
    echo "Housekeeping: Data already exists.<br>";
} 