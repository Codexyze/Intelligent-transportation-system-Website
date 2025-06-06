<?php
header('Content-Type: application/json');

$video_id = $_GET['video_id'] ?? null;

if (!$video_id) {
    echo json_encode(['error' => 'Video ID is required']);
    exit;
}

try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "its_db";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT video_id, title, original_path FROM videos WHERE video_id = ?");
    $stmt->execute([$video_id]);
    
    if ($video = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Convert path to web format if needed
        $webPath = str_starts_with($video['original_path'], '/') 
            ? $video['original_path'] 
            : '/' . $video['original_path'];
            
        echo json_encode([
            'video_id' => $video['video_id'],
            'title' => $video['title'],
            'original_path' => $webPath
        ]);
    } else {
        echo json_encode(['error' => 'Video not found']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>