<?php
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$video_id = $_GET['video_id'] ?? null;

if (!$video_id) {
    echo json_encode(['error' => 'Video ID is required']);
    exit;
}

try {
    $conn = getDBConnection();
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    $stmt = $conn->prepare("SELECT video_id, title, original_path FROM videos WHERE video_id = ?");
    $stmt->execute([$video_id]);
    
    if ($video = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Construct the web-safe path
        $webPath = str_replace(' ', '_', $video['original_path']);
        
        // Debug information
        $debug = [
            'requested_video_id' => $video_id,
            'file_path' => $_SERVER['DOCUMENT_ROOT'] . $webPath,
            'file_exists' => file_exists($_SERVER['DOCUMENT_ROOT'] . $webPath) ? 'yes' : 'no'
        ];
        
        echo json_encode([
            'status' => 'success',
            'video_id' => $video['video_id'],
            'title' => $video['title'],
            'original_path' => $webPath,
            'debug_info' => $debug
        ]);
    } else {
        echo json_encode([
            'error' => 'Video not found',
            'video_id' => $video_id
        ]);
    }
} catch(Exception $e) {
    echo json_encode([
        'error' => 'Server error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>