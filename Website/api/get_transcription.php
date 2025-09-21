<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 0;
$lang_code = isset($_GET['lang_code']) ? $_GET['lang_code'] : 'en';

// Log incoming request
error_log("Transcription requested for video_id: $video_id, lang_code: $lang_code");

if (!$video_id) {
    echo json_encode(['error' => 'Invalid video ID']);
    exit;
}

try {
    $conn = getDBConnection();
    
    // Get transcription path from database
    $stmt = $conn->prepare("SELECT transcript_path FROM transcriptions 
                           WHERE video_id = ? AND lang_code = ?");
    $stmt->execute([$video_id, $lang_code]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        error_log("No transcription found in database for video_id: $video_id, lang_code: $lang_code");
        echo json_encode(['error' => 'Transcription not available in database']);
        exit;
    }
    
    $transcript_path = $result['transcript_path'];
    error_log("Looking for transcript at: " . $transcript_path);
    
    // Check if the path is a Firebase URL
    if (strpos($transcript_path, 'http') === 0) {
        // It's a URL, fetch the content directly
        $transcript = file_get_contents($transcript_path);
        if ($transcript === false) {
            error_log("Failed to fetch transcript from URL: " . $transcript_path);
            echo json_encode(['error' => 'Failed to fetch transcript from URL']);
            exit;
        }
    } else {
        // It's a local file path
        $full_path = $_SERVER['DOCUMENT_ROOT'] . $transcript_path;
        if (!file_exists($full_path)) {
            error_log("Transcript file not found at: " . $full_path);
            echo json_encode(['error' => 'Transcription file not found']);
            exit;
        }
        $transcript = file_get_contents($full_path);
        if ($transcript === false) {
            error_log("Failed to read transcript file: " . $full_path);
            echo json_encode(['error' => 'Failed to read transcript file']);
            exit;
        }
    }
    
    echo json_encode(['transcript' => $transcript]);
    
} catch (Exception $e) {
    error_log("Error in get_transcription.php: " . $e->getMessage());
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}