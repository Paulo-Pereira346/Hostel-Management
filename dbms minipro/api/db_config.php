<?php
// Set the content type for all API responses to JSON
header('Content-Type: application/json');

// --- CORS HEADERS ---
// Allow requests from any origin (e.g., your frontend running on a different port)
header('Access-Control-Allow-Origin: *');
// Allow specific HTTP methods
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
// Allow specific headers, like Content-Type
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight "OPTIONS" request (sent by browsers to check permissions)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// --- DATABASE CONNECTION ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
// Change this to your MySQL password. Default for XAMPP is often an empty string ''.
define('DB_PASS', ''); 
define('DB_NAME', 'hostel_management');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // Use json_encode for all responses, even errors
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Set the charset to utf8 for proper encoding
$conn->set_charset("utf8");
?>
