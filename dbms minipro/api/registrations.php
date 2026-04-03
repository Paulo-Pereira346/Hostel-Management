<?php
require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM registration ORDER BY reg_id DESC");
        $registrations = [];
        while ($row = $result->fetch_assoc()) {
            $registrations[] = $row;
        }
        echo json_encode($registrations);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $conn->prepare("INSERT INTO registration (person_id, staff_id, room_no, reg_date) VALUES (?, ?, ?, ?)");
        // 'iiss' = integer, integer, string, string
        $stmt->bind_param("iiss", $data['person_id'], $data['staff_id'], $data['room_no'], $data['reg_date']);
        
        if ($stmt->execute()) {
            $data['reg_id'] = $conn->insert_id;
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $id = isset($_GET['reg_id']) ? intval($_GET['reg_id']) : 0;
        
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM registration WHERE reg_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Registration deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Registration ID required']);
        }
        break;
}

$conn->close();
?>
