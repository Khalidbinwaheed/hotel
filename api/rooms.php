<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

header('Content-Type: application/json');

// Get database connection
$db = getDBConnection();

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Get single room or all rooms
        if(isset($_GET['id'])) {
            $stmt = $db->prepare("SELECT * FROM rooms WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($room);
        } else {
            $stmt = $db->query("SELECT * FROM rooms ORDER BY room_number");
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rooms);
        }
        break;

    case 'POST':
        // Create new room
        $data = $_POST;
        
        try {
            $stmt = $db->prepare("
                INSERT INTO rooms (room_number, type, floor, rate, status, amenities)
                VALUES (?, ?, ?, ?, 'available', ?)
            ");
            
            $amenities = isset($data['amenities']) ? json_encode($data['amenities']) : '[]';
            
            $stmt->execute([
                $data['roomNumber'],
                $data['roomType'],
                $data['floor'],
                $data['rate'],
                $amenities
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Room created successfully']);
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error creating room: ' . $e->getMessage()]);
        }
        break;

    case 'PUT':
        // Update existing room
        parse_str(file_get_contents("php://input"), $data);
        
        try {
            $stmt = $db->prepare("
                UPDATE rooms 
                SET room_number = ?, type = ?, floor = ?, rate = ?, amenities = ?
                WHERE id = ?
            ");
            
            $amenities = isset($data['amenities']) ? json_encode($data['amenities']) : '[]';
            
            $stmt->execute([
                $data['roomNumber'],
                $data['roomType'],
                $data['floor'],
                $data['rate'],
                $amenities,
                $data['roomId']
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Room updated successfully']);
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error updating room: ' . $e->getMessage()]);
        }
        break;

    case 'DELETE':
        // Delete room
        if(isset($_GET['id'])) {
            try {
                $stmt = $db->prepare("DELETE FROM rooms WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                echo json_encode(['success' => true, 'message' => 'Room deleted successfully']);
            } catch(PDOException $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error deleting room: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Room ID is required']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
} 