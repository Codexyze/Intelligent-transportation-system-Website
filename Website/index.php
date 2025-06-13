<!-- php file for the webpage -->
<?php
require_once __DIR__ . '/includes/db.php';

// Get available videos
$conn = getDBConnection();
$videos = [];
if ($conn) {
    $stmt = $conn->query("SELECT video_id, title FROM videos ORDER BY created_at DESC");
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- HTML file for the webpage -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="vky6366">
    <meta name="last-modified" content="2025-06-08 03:51:36 UTC">
    <title>ITS Learning Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Top Contact Bar -->
    <div class="real">
        <!-- subdiv -->
        <div class="container">
            <i class="phone"></i>
            <b></b>
            <i class="envelope_icon"></i>
        </div>
    </div>


    <!-- Header Section -->
    <div class="container1">
        <!-- subdiv -->
        <div class="row1">
            <!-- founders Logo -->
            <div class="site_header_1">
                <a class="back" href="https://aitmbgm.ac.in">
                    <img class="photo" src="https://aitmbgm.ac.in/wp-content/themes/aitmbgm-20/images/Suresh-Angadi.jpg"
                        alt="AITMBGM" title="AITMBGM">
                </a>
            </div>

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

            <!-- AITM Logo -->
            <div class="site_header_4">
                <a class="back" href="https://aitmbgm.ac.in">
                    <img class="photo" src="https://aitmbgm.ac.in/wp-content/themes/aitmbgm-20/images/aitm-logo.png"
                        alt="AITM" title="AITM">
                </a>
            </div>

        </div>
    </div>


    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="#" class="active">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>

        <!-- Language Selector -->
        <div class="language-dropdown">
            <label for="languageSelect">Language:</label>
            <select id="languageSelect">
                <option value="en">English</option>
                <option value="fr">French</option>
                <option value="es">Spanish</option>
                <option value="de">German</option>
                <option value="ru">Russian</option>
                <option value="it">Italian</option>
                <option value="hi">Hindi</option>
                <option value="kn">Kannada</option>
                <option value="mr">Marathi</option>
                <option value="bn">Bengali</option>
                <option value="gu">Gujarati</option>
                <option value="ta">Tamil</option>
                <option value="te">Telugu</option>
                <option value="ur">Urdu</option>
            </select>
        </div>
    </nav>


    <!-- Main Content Container -->
    <div class="container_page_layout">
        <!-- Video List Sidebar -->
        <div class="video-list">
            <h2>Available Videos</h2>
            <ul id="videosList">
                <?php foreach ($videos as $video): ?>
                <li>
                    <a href="#" onclick="selectVideo(<?php echo $video['video_id']; ?>); return false;">
                        <?php echo htmlspecialchars($video['title']); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Video Player -->
            <div class="video-container">
                <div class="video-wrapper">
                    <video id="videoElement" controlsList="nodownload">
                        Your browser does not support the video element.
                    </video>
                    <div class="video-controls">
                        <button id="playPauseBtn">Play</button>
                        <div id="progressBar">
                            <div id="progress"></div>
                        </div>
                        <div class="time-display">
                            <span id="currentTime">00:00</span> /
                            <span id="duration">00:00</span>
                        </div>
                        <div class="volume-control">
                            <label for="volumeControl">Volume:</label>
                            <input type="range" id="volumeControl" min="0" max="1" step="0.1" value="1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transcription and Description Area -->
            <div class="transcription-container">
                <div class="content-toggle">
                    <button id="showTranscription" class="toggle-btn active">Transcription</button>
                    <button id="showDescription" class="toggle-btn">Description</button>
                    <button id="showFeedback" class="toggle-btn feedback-btn">Provide Feedback</button>
                </div>
                <div id="contentArea">
                    <div id="transcriptionText" class="transcription-text content-section active"></div>
                    <div id="descriptionText" class="description-text content-section"></div>
                    <div id="feedbackForm" class="feedback-section content-section">
                        <form action="submit_feedback.php" method="POST">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="feedback">Feedback:</label>
                                <textarea id="feedback" name="feedback" required></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Submit Feedback</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Information Panel -->
    <div id="debugInfo" style="
        position: fixed;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 15px;
        border-radius: 5px;
        font-family: monospace;
        z-index: 9999;
        max-width: 300px;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
    ">
        <h3 style="margin: 0 0 10px 0; color: #00ff00;">Debug Information</h3>
        <p>Current Video ID: <span id="currentVideoIdDisplay">none</span></p>
        <p>Current Language: <span id="currentLanguageDisplay">none</span></p>
        <p>Audio Loaded: <span id="audioLoadedDisplay">false</span></p>
        <p>Last Event: <span id="lastEventDisplay">none</span></p>
        <p>Last Updated: <span id="lastUpdatedDisplay"><?php echo date('Y-m-d H:i:s'); ?></span></p>
        <p>Current User: <span id="currentUserDisplay">vky6366</span></p>
    </div>
    <div class="show-feedback-section">
        <a href="view_feedbacks.php" class="show-feedback-btn">
            <i class="fas fa-comments"></i>
            View All Feedbacks
        </a>
    </div>
    <!-- Footer -->
    <footer>
        <!-- Show All Feedbacks Button -->

        <div class="footer-content">
            <h2>Angadi Institute Of Technology And Management</h2>
            <p>Last Updated: 2025-06-08 03:51:36 UTC</p>
            <p>Developer: vky6366</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/player.js"></script>
    <script>
    console.log("Test script loaded");
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Test DOMContentLoaded fired");
        const testBtn = document.getElementById('showTranscription');
        console.log("Test button found:", testBtn);
        // Get modal elements
        const modal = document.getElementById('feedbackModal');
        const btn = document.getElementById('feedbackBtn');
        const span = document.getElementsByClassName('close')[0];
        const form = document.getElementById('feedbackForm');
        const currentDateTimeElement = document.getElementById('currentDateTime');
        const currentUserElement = document.getElementById('currentUser');

        // Update date/time and user
        function updateDateTime() {
            const now = new Date();
            const formatted = now.getUTCFullYear() + '-' +
                String(now.getUTCMonth() + 1).padStart(2, '0') + '-' +
                String(now.getUTCDate()).padStart(2, '0') + ' ' +
                String(now.getUTCHours()).padStart(2, '0') + ':' +
                String(now.getUTCMinutes()).padStart(2, '0') + ':' +
                String(now.getUTCSeconds()).padStart(2, '0');
            currentDateTimeElement.textContent = formatted;
        }

        // Open modal
        btn.onclick = function() {
            modal.style.display = 'block';
            setTimeout(() => modal.classList.add('show'), 10);
            updateDateTime();
        }

        // Close modal
        function closeModal() {
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        span.onclick = closeModal;

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('submit_feedback.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        form.reset();
                        closeModal();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Error submitting feedback. Please try again.');
                });
        });

        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'block') {
                closeModal();
            }
        });
    });
    </script>
    <script>
    // Initialize debug display updates
    function updateDebugTimestamp() {
        document.getElementById('lastUpdatedDisplay').textContent = new Date().toISOString();
    }

    // Update debug timestamp every minute
    setInterval(updateDebugTimestamp, 60000);

    // Make debug panel draggable
    const debugPanel = document.getElementById('debugInfo');
    let isDragging = false;
    let currentX;
    let currentY;
    let initialX;
    let initialY;

    debugPanel.addEventListener('mousedown', dragStart);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);

    function dragStart(e) {
        initialX = e.clientX - debugPanel.offsetLeft;
        initialY = e.clientY - debugPanel.offsetTop;
        isDragging = true;
    }

    function drag(e) {
        if (isDragging) {
            e.preventDefault();
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;
            debugPanel.style.left = currentX + 'px';
            debugPanel.style.top = currentY + 'px';
        }
    }

    function dragEnd() {
        isDragging = false;
    }
    </script>
</body>

</html>