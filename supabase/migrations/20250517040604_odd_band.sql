require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';  

-- Create the hotel management database
USE hotel_management;


-- Users table for system access
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'manager', 'receptionist', 'staff') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Room categories (e.g., Standard, Deluxe, Suite)
CREATE TABLE room_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    amenities TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rooms table
CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(20) NOT NULL UNIQUE,
    category_id INT NOT NULL,
    status ENUM('available', 'occupied', 'maintenance', 'reserved') NOT NULL DEFAULT 'available',
    floor VARCHAR(10) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES room_categories(id) ON DELETE CASCADE
);

-- Guests information
CREATE TABLE guests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    id_type VARCHAR(50),
    id_number VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    guest_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    adults INT NOT NULL DEFAULT 1,
    children INT DEFAULT 0,
    status ENUM('confirmed', 'checked_in', 'checked_out', 'cancelled') NOT NULL,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    special_requests TEXT,
    created_by INT,
    FOREIGN KEY (guest_id) REFERENCES guests(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Payments
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reservation_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method ENUM('cash', 'credit_card', 'debit_card', 'bank_transfer', 'online') NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL,
    transaction_id VARCHAR(100),
    notes TEXT,
    created_by INT,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Services offered by the hotel
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('food', 'spa', 'laundry', 'transport', 'other') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Service orders for guests
CREATE TABLE service_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reservation_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'delivered', 'cancelled') NOT NULL,
    notes TEXT,
    created_by INT,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Housekeeping tasks
CREATE TABLE housekeeping (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed') NOT NULL,
    notes TEXT,
    assigned_to INT,
    scheduled_date DATE NOT NULL,
    completed_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

-- Insert default admin user
INSERT INTO users (username, password, name, email, role)
VALUES ('admin', '1234', 'System Administrator', 'admin@hotel.com', 'admin');

-- Insert sample room categories
INSERT INTO room_categories (name, description, base_price, capacity, amenities)
VALUES 
('Standard', 'Comfortable room with essential amenities', 100.00, 2, 'WiFi, TV, Air Conditioning'),
('Deluxe', 'Spacious room with premium amenities', 150.00, 2, 'WiFi, TV, Air Conditioning, Mini Bar, City View'),
('Suite', 'Luxury suite with separate living area', 250.00, 4, 'WiFi, TV, Air Conditioning, Mini Bar, Ocean View, Living Room, Kitchen');