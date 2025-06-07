<?php
require_once '../includes/db.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$video_id = $_GET['video_id'] ?? null;
$lang_code = $_GET['lang_code'] ?? 'en';

if (!$video_id) {
    echo json_encode(['error' => 'Video ID is required']);
    exit;
}

try {
    $conn = getDBConnection();
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    $stmt = $conn->prepare("
        SELECT t.transcript_path, v.title 
        FROM transcriptions t
        JOIN videos v ON t.video_id = v.video_id
        WHERE t.video_id = ? AND t.lang_code = ?
    ");
    $stmt->execute([$video_id, $lang_code]);
    
    if ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $transcriptPath = $_SERVER['DOCUMENT_ROOT'] . $data['transcript_path'];
        
        if (file_exists($transcriptPath)) {
            $transcript = file_get_contents($transcriptPath);
            echo json_encode([
                'status' => 'success',
                'title' => $data['title'],
                'transcript' => $transcript,
                'language' => $lang_code
            ]);
        } else {
            echo json_encode([
                'error' => 'Transcript file not found',
                'path' => $data['transcript_path']
            ]);
        }
    } else {
        echo json_encode(['error' => 'Transcript not found']);
    }
} catch(Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>