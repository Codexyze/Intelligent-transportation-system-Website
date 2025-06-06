<?php
header('Content-Type: application/json');

$video_id = $_GET['video_id'] ?? null;

if (!$video_id) {
    echo json_encode(['error' => 'Video ID is required']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=its_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT original_path FROM videos WHERE video_id = ?");
    $stmt->execute([$video_id]);
    
    if ($video = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            'video_path' => $video['original_path']
        ]);
    } else {
        echo json_encode(['error' => 'Video not found']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}