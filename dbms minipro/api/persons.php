<?php
// Include the database config and CORS headers
require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all persons
        $result = $conn->query("SELECT * FROM person ORDER BY person_id DESC");
        $persons = [];
        while ($row = $result->fetch_assoc()) {
            $persons[] = $row;
        }
        echo json_encode($persons);
        break;

    case 'POST':
        // Add a new person
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO person (name, contact, address, role) VALUES (?, ?, ?, ?)");
        // 'ssss' means four string parameters
        $stmt->bind_param("ssss", $data['name'], $data['contact'], $data['address'], $data['role']);
        
        if ($stmt->execute()) {
            $data['person_id'] = $conn->insert_id;
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Delete a person
        $id = isset($_GET['person_id']) ? intval($_GET['person_id']) : 0;
        
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM person WHERE person_id = ?");
            // 'i' means one integer parameter
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Person deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Person ID required']);
        }
        break;
}

$conn->close();
?>
