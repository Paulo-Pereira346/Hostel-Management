<?php
require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM room ORDER BY room_no ASC");
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        echo json_encode($rooms);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $conn->prepare("INSERT INTO room (room_no, room_type, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['room_no'], $data['room_type'], $data['status']);
        
        if ($stmt->execute()) {
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Note: Your JS sends 'room_no' as the parameter, not 'id'
        $room_no = isset($_GET['room_no']) ? $conn->real_escape_string($_GET['room_no']) : '';
        
        if (!empty($room_no)) {
            $stmt = $conn->prepare("DELETE FROM room WHERE room_no = ?");
            $stmt->bind_param("s", $room_no);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Room deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Room No required']);
        }
        break;
}

$conn->close();
?>
