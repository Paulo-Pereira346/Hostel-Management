<?php
require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM complaint ORDER BY complaint_id DESC");
        $complaints = [];
        while ($row = $result->fetch_assoc()) {
            $complaints[] = $row;
        }
        echo json_encode($complaints);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $conn->prepare("INSERT INTO complaint (person_id, description, date) VALUES (?, ?, ?)");
        // 'iss' = integer, string, string
        $stmt->bind_param("iss", $data['person_id'], $data['description'], $data['date']);
        
        if ($stmt->execute()) {
            $data['complaint_id'] = $conn->insert_id;
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $id = isset($_GET['complaint_id']) ? intval($_GET['complaint_id']) : 0;
        
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM complaint WHERE complaint_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Complaint deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Complaint ID required']);
        }
        break;
}

$conn->close();
?>
