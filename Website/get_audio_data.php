<?php
header('Content-Type: application/json');

$video_id = $_GET['video_id'] ?? null;
$lang_code = $_GET['lang_code'] ?? null;

if (!$video_id || !$lang_code) {
    echo json_encode(['error' => 'Video ID and language code are required']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=its_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT audio_path FROM Translation WHERE video_id = ? AND lang_code = ?");
    $stmt->execute([$video_id, $lang_code]);
    
    if ($audio = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            'audio_path' => $audio['audio_path']
        ]);
    } else {
        echo json_encode(['error' => 'Audio not found for this language']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}