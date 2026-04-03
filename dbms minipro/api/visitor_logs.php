<?php
require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM visitor_log ORDER BY visitor_id DESC");
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        echo json_encode($logs);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $conn->prepare("INSERT INTO visitor_log (visitor_name, visited_person_id, date) VALUES (?, ?, ?)");
        // 'sis' = string, integer, string
        $stmt->bind_param("sis", $data['visitor_name'], $data['visited_person_id'], $data['date']);
        
        if ($stmt->execute()) {
            $data['visitor_id'] = $conn->insert_id;
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $id = isset($_GET['visitor_id']) ? intval($_GET['visitor_id']) : 0;
        
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM visitor_log WHERE visitor_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Visitor log deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Visitor ID required']);
        }
        break;
}

$conn->close();
?>
