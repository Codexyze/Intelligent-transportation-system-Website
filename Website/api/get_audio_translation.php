<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request
file_put_contents('audio_translation_log.txt', 
    date('Y-m-d H:i:s') . " - Received request\n" . 
    "GET params: " . print_r($_GET, true) . "\n", 
    FILE_APPEND);

require_once '../includes/db.php';

$video_id = isset($_GET['video_id']) ? intval($_GET['video_id']) : 0;
$lang_code = isset($_GET['lang_code']) ? $_GET['lang_code'] : '';

if (!$video_id || !$lang_code) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

try {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT audio_path FROM audio_translations WHERE video_id = ? AND lang_code = ?");
    $stmt->execute([$video_id, $lang_code]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Log the query result
    file_put_contents('audio_translation_log.txt', 
        date('Y-m-d H:i:s') . " - Query result: " . print_r($result, true) . "\n", 
        FILE_APPEND);
    
    if ($result) {
        echo json_encode([
            'audio_path' => $result['audio_path'],
            'lang_code' => $lang_code
        ]);
    } else {
        echo json_encode([
            'error' => 'No audio translation found for this language'
        ]);
    }
    
} catch (Exception $e) {
    file_put_contents('audio_translation_log.txt', 
        date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", 
        FILE_APPEND);
    
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}