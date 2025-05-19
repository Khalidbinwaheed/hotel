CREATE TABLE IF NOT EXISTS rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    type ENUM('standard', 'deluxe', 'suite') NOT NULL,
    floor INT NOT NULL,
    rate DECIMAL(10,2) NOT NULL,
    status ENUM('available', 'occupied', 'maintenance') NOT NULL DEFAULT 'available',
    amenities JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add indexes for better performance
CREATE INDEX idx_room_number ON rooms(room_number);
CREATE INDEX idx_room_status ON rooms(status);
CREATE INDEX idx_room_type ON rooms(type); 