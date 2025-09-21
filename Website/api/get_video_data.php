<?php
require_once '../includes/db.php';
require_once '../includes/config.php'; // Make sure this path is correct

header('Content-Type: application/json');

$video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 0;

if (!$video_id) {
    echo json_encode(['error' => 'Invalid video ID']);
    exit;
}

try {
    $conn = getDBConnection();
    
    // Get all video details including description
    $stmt = $conn->prepare("
        SELECT v.video_id, 
               v.title, 
               v.original_path,
               v.description,
               v.created_at,
               t.transcript_path
        FROM videos v
        LEFT JOIN transcriptions t ON v.video_id = t.video_id AND t.lang_code = 'en'
        WHERE v.video_id = ?
    ");
    
    $stmt->execute([$video_id]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$video) {
        echo json_encode(['error' => 'Video not found']);
        exit;
    }

    // Construct the full server path
    $server_path = $video['original_path'];
    
    // Check if the video file exists - only for local files
    $file_exists = strpos($server_path, 'http') === 0 ? true : file_exists($server_path);

    // If there's a description, process it
    $description = !empty($video['description']) ? $video['description'] : 'No description available for this video.';

    // Debug information
    error_log("Loading video ID: " . $video_id);
    error_log("Video path: " . $server_path);
    error_log("File exists: " . ($file_exists ? 'yes' : 'no'));
    error_log("Description length: " . strlen($description));

    $response = [
        'status' => 'success',
        'video_id' => $video['video_id'],
        'title' => $video['title'],
        'original_path' => $video['original_path'],
        'description' => $description,
        'created_at' => $video['created_at'],
        'transcript_path' => $video['transcript_path'],
        'debug_info' => [
            'requested_video_id' => $video_id,
            'file_path' => $server_path,
            'file_exists' => $file_exists ? 'yes' : 'no',
            'description_available' => !empty($video['description']) ? 'yes' : 'no'
        ]
    ];

    // Log the response for debugging
    error_log("Sending response: " . json_encode($response));
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log('Video data error: ' . $e->getMessage());
    echo json_encode([
        'error' => 'Error loading video data',
        'debug_info' => [
            'message' => $e->getMessage(),
            'video_id' => $video_id
        ]
    ]);
}
?>