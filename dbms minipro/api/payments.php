<?php
require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM payment ORDER BY payment_id DESC");
        $payments = [];
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
        echo json_encode($payments);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $conn->prepare("INSERT INTO payment (person_id, amount, date) VALUES (?, ?, ?)");
        // 'ids' = integer, double (for decimal), string (for date)
        $stmt->bind_param("ids", $data['person_id'], $data['amount'], $data['date']);
        
        if ($stmt->execute()) {
            $data['payment_id'] = $conn->insert_id;
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $id = isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;
        
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM payment WHERE payment_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Payment deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Payment ID required']);
        }
        break;
}

$conn->close();
?>
