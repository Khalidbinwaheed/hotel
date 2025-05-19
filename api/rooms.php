<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = Database::getConnection();

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single room
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT r.*, c.name as category_name, c.base_price 
                                  FROM rooms r 
                                  LEFT JOIN room_categories c ON r.category_id = c.id 
                                  WHERE r.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            // Get all rooms
            $sql = "SELECT r.*, c.name as category_name, c.base_price 
                   FROM rooms r 
                   LEFT JOIN room_categories c ON r.category_id = c.id 
                   ORDER BY r.room_number";
            $result = $conn->query($sql);
            $rooms = [];
            while ($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
            echo json_encode($rooms);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        $room_number = $data['room_number'];
        $category_id = $data['category_id'];
        $floor = $data['floor'];
        $status = $data['status'] ?? 'available';
        $notes = $data['notes'] ?? '';

        $stmt = $conn->prepare("INSERT INTO rooms (room_number, category_id, floor, status, notes) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiss", $room_number, $category_id, $floor, $status, $notes);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Room ID is required']);
            break;
        }

        $id = $data['id'];
        $room_number = $data['room_number'];
        $category_id = $data['category_id'];
        $floor = $data['floor'];
        $status = $data['status'];
        $notes = $data['notes'] ?? '';

        $stmt = $conn->prepare("UPDATE rooms 
                              SET room_number = ?, category_id = ?, floor = ?, status = ?, notes = ? 
                              WHERE id = ?");
        $stmt->bind_param("siissi", $room_number, $category_id, $floor, $status, $notes, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'Room ID is required']);
            break;
        }

        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
} 