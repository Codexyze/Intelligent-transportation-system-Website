<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "its_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Query to fetch only video_id and title
$sql = "SELECT video_id, title FROM videos";
$result = $conn->query($sql);

if (!$result) {
    die(json_encode(['error' => "Query failed: " . $conn->error]));
}

$videos = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($videos);

$conn->close();
?>