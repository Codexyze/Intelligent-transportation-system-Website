<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the form data
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$feedback = filter_input(INPUT_POST, 'feedback', FILTER_SANITIZE_STRING);

// Validate inputs
if (!$name || !$email || !$feedback) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

try {
    $conn = getDBConnection();
    
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    $stmt = $conn->prepare("INSERT INTO feedback (name, email, feedback) VALUES (:name, :email, :feedback)");
    
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':feedback', $feedback);
    
    $stmt->execute();
    
    echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error submitting feedback']);
}
?>