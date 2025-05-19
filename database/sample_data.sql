USE hotel_management;

-- Insert sample room categories
INSERT INTO room_categories (name, description, base_price, capacity, amenities) VALUES
('Standard Room', 'Comfortable room with essential amenities', 100.00, 2, '{"wifi": true, "tv": true, "ac": true}'),
('Deluxe Room', 'Spacious room with premium amenities', 150.00, 2, '{"wifi": true, "tv": true, "ac": true, "minibar": true, "coffee_maker": true}'),
('Suite', 'Luxury suite with separate living area', 250.00, 4, '{"wifi": true, "tv": true, "ac": true, "minibar": true, "coffee_maker": true, "jacuzzi": true, "living_room": true}'),
('Executive Suite', 'Premium suite with additional services', 350.00, 4, '{"wifi": true, "tv": true, "ac": true, "minibar": true, "coffee_maker": true, "jacuzzi": true, "living_room": true, "butler_service": true}');

-- Insert sample rooms
INSERT INTO rooms (room_number, category_id, floor, status) VALUES
('101', 1, 1, 'available'),
('102', 1, 1, 'available'),
('201', 2, 2, 'available'),
('202', 2, 2, 'available'),
('301', 3, 3, 'available'),
('302', 3, 3, 'available'),
('401', 4, 4, 'available'),
('402', 4, 4, 'available');

-- Insert sample guests
INSERT INTO guests (first_name, last_name, email, phone, address, id_type, id_number) VALUES
('John', 'Doe', 'john.doe@email.com', '+1234567890', '123 Main St, City', 'passport', 'P123456'),
('Jane', 'Smith', 'jane.smith@email.com', '+1987654321', '456 Oak St, City', 'national_id', 'N789012'),
('Robert', 'Johnson', 'robert.j@email.com', '+1122334455', '789 Pine St, City', 'drivers_license', 'DL345678');

-- Insert sample services
INSERT INTO services (name, description, price, category) VALUES
('Room Cleaning', 'Daily room cleaning service', 0.00, 'room_service'),
('Laundry Service', 'Professional laundry and dry cleaning', 25.00, 'laundry'),
('Spa Treatment', 'Relaxing spa treatment', 100.00, 'spa'),
('Breakfast Buffet', 'Continental breakfast buffet', 25.00, 'dining'),
('Airport Transfer', 'Private airport transfer service', 50.00, 'other');

-- Insert sample reservations
INSERT INTO reservations (guest_id, room_id, check_in, check_out, adults, children, status, created_by) VALUES
(1, 1, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY), 2, 0, 'confirmed', 1),
(2, 3, DATE_ADD(CURRENT_DATE, INTERVAL 2 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 4 DAY), 2, 1, 'pending', 1),
(3, 5, DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 2, 2, 'confirmed', 1);

-- Insert sample housekeeping tasks
INSERT INTO housekeeping (room_id, status, type, assigned_to) VALUES
(1, 'pending', 'cleaning', 1),
(2, 'in_progress', 'maintenance', 1),
(3, 'completed', 'inspection', 1);

-- Insert sample service orders
INSERT INTO service_orders (reservation_id, service_id, quantity, status) VALUES
(1, 1, 1, 'completed'),
(1, 3, 1, 'pending'),
(2, 2, 1, 'in_progress');

-- Insert sample billing records
INSERT INTO billing (reservation_id, amount, payment_status, payment_method) VALUES
(1, 200.00, 'paid', 'credit_card'),
(2, 300.00, 'pending', 'cash'),
(3, 500.00, 'partial', 'bank_transfer');

-- Insert sample notifications
INSERT INTO notifications (user_id, title, message, type) VALUES
(1, 'New Reservation', 'New reservation received for Room 101', 'info'),
(1, 'Check-out Reminder', 'Room 201 check-out in 1 hour', 'warning'),
(1, 'Payment Received', 'Payment received for Reservation #1', 'success'); 