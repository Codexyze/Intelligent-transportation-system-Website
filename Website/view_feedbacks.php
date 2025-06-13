<?php
require_once __DIR__ . '/includes/db.php';

// Get all feedbacks
$conn = getDBConnection();
$feedbacks = [];
if ($conn) {
    // Modified query to order by id instead of created_at since created_at doesn't exist
    $stmt = $conn->query("SELECT id, name, email, feedback FROM feedback ORDER BY id DESC");
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback List - ITS Learning Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header Section -->
    <div class="container1">
        <div class="row1">
            <!-- AITMBGM Logo -->
            <div class="site_header_2">
                <a class="back" href="https://aitmbgm.ac.in">
                    <img class="photo" src="https://aitmbgm.ac.in/wp-content/themes/aitmbgm-20/images/aitmbgm-logo.png"
                        alt="AITMBGM" title="AITMBGM">
                </a>
            </div>

            <!-- Institution Details -->
            <div class="site_header_3">
                <h6>SURESH ANGADI EDUCATION FOUNDATIONS</h6>
                <h2>ANGADI INSTITUTE OF TECHNOLOGY AND MANAGEMENT</h2>
                <span>Approved by AICTE, New Delhi, Affiliated to VTU, Belagavi. <br>Accredited by *NBA and NAAC</span>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#" class="active">Feedbacks</a></li>
        </ul>
    </nav>

    <!-- Feedback List Container -->
    <div class="feedback-list-container">
        <div class="feedback-header">
            <h1>User Feedbacks</h1>
            <a href="index.php" class="back-btn">Back to Video Player</a>
        </div>
        
        <?php if (empty($feedbacks)): ?>
            <div class="no-feedback">
                <p>No feedbacks available yet.</p>
            </div>
        <?php else: ?>
            <div class="feedback-grid">
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="feedback-card">
                        <div class="feedback-info">
                            <h3><?php echo htmlspecialchars($feedback['name']); ?></h3>
                            <span class="feedback-metadata">Feedback ID: <?php echo $feedback['id']; ?></span>
                        </div>
                        <div class="feedback-content">
                            <p><?php echo nl2br(htmlspecialchars($feedback['feedback'])); ?></p>
                        </div>
                        <div class="feedback-footer">
                            <span class="feedback-email"><?php echo htmlspecialchars($feedback['email']); ?></span>
                        </div>
                    </div>
                    <br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <h2>Angadi Institute Of Technology And Management</h2>
        </div>
    </footer>
</body>
</html>