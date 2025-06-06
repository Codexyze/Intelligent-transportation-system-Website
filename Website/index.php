<!DOCTYPE html>
<html>
<head>
    <title>Video Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
                * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            background-color: rgb(238, 235, 240);
            height: 40px;
            width: auto;
            display: flex;
            flex-direction: row;
            justify-content: start;
        }
        body {
            padding: 0;
            color: rgb(255, 255, 255);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            color: rgb(43, 69, 152);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: rgb(43, 69, 152);
            border-radius: 2px;
        }
        .main-content {
            display: block;
            padding: 20px;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }
        .department-section {
            /* Updated to take full width */
            width: 100%;
            padding-right: 20px;
        }
        .content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }
        .row {
            background-color: rgb(238, 235, 240);
            color: rgb(29, 28, 28);
            height: 50%;
            width: 20%;
            margin-right: 60%;
            margin-top: 10px;
            margin-left: 10px;
        }
        .real {
            background-color: white;
            color: blue;
            text-align: center;
            height: auto;
            width: auto;
            margin: 0;
            padding: 0;
        }
        .container1 {
            background-color: rgb(255, 255, 255);
            color: rgb(43, 69, 152);
            height: 120px;
            width: auto;
            position: sticky;
        }
        .row1 {
            background-color: rgb(255, 255, 255);
            align-items: center;
            justify-content: space-between;
            text-align: center;
            display: flex;
            flex-direction: row;
            width: 80%;
            margin-left: 10%;
        }
        .site_header_1 {
            height: 100px;
            width: 100px;
        }
        .photo {
            height: 100%;
            width: 100%;
            margin-top:2px;
        }
        .navbar {
            background-color: rgb(43, 69, 152);
            color: white;
            height: 40px;
            width: auto;
            text-decoration: none;
            font-size: medium;
            padding: 10px 20px;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            display: inline;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
        }

        .navbar a:hover {
            background-color: #555;
            border-radius: 4px;
        }
        footer {
            background-color: rgb(43, 69, 152);
            color: rgb(255, 255, 255);
            text-align: center;
            padding: 10px 20px;
            bottom: 0;
            width: 100%;
            position: absolute;
        }
        .content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
            padding: 0 20px;
        }
        .program-box {
            width: 250px;
            background-color: #fff;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin: 20px;
        }
        
        .program-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        .program-box img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .program-box a {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            color: rgb(43, 69, 152);
            text-decoration: none;
            padding: 10px 0;
            transition: color 0.3s ease;
        }
        
        .program-box a:hover {
            color: #7b38f7;
            text-decoration: none;
            transform: scale(1.02);
        }

        .programs-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding: 20px;
            gap: 30px;
        }

        .main-content {
            display: flex;
            margin: 20px;
            min-height: calc(100vh - 340px);
        }

        .video-list {
            width: 300px;
            background: #f7f9fa;
            border-right: 1px solid #e1e1e1;
            position: absolute;
            top: 200px; /* Adjust this value based on your header height */
            bottom: 120px; /* Adjust this value based on your footer height */
            overflow-y: auto;
            left: 0;
        }

        .video-item {
            padding: 15px;
            border-bottom: 1px solid #e1e1e1;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .video-item:hover {
            background-color: #eef2ff;
        }

        .video-item h3 {
            color: #1f1f1f;
            font-size: 16px;
        }

        .video-player {
            flex-grow: 1;
            padding: 20px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #debug {
            padding: 10px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            margin: 10px;
        }
        .video-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            margin-left: 320px; /* Add space for the video list */
        }

        .video-wrapper {
            position: relative;
            width: 100%;
            background: #000;
            aspect-ratio: 16 / 9; /* Maintain video aspect ratio */
        }

        #videoElement {
            width: 100%;
            height: 100%;
            background: #000;
        }

        .controls-wrapper {
            margin-top: 10px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }

        .language-selector {
            margin: 10px 0;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .control-button {
            padding: 8px 15px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            background: rgb(43, 69, 152);
            color: white;
            cursor: pointer;
        }

        .control-button:hover {
            background: rgb(63, 89, 172);
        }

        .time-display {
            display: inline-block;
            margin: 0 10px;
            font-family: monospace;
        }

        #progressBar {
            width: 100%;
            height: 5px;
            background: #ddd;
            margin: 10px 0;
            cursor: pointer;
        }

        #progress {
            width: 0%;
            height: 100%;
            background: rgb(43, 69, 152);
            transition: width 0.1s linear;
        }
    </style>
</head>
<body>
    <div class="real">
        <div class="container">
            <div class="row">
                <div class="site_topbar">
                    <i class="phone"></i> <b></b>
                    <i class="envelope_icon"></i> 
                </div>
            </div>
        </div>
    </div>

    <div class="container1">
        <div class="row1">
            <div class="site_header_1">
                <h2 class="web_title">
                    <a class="back" href="https://aitmbgm.ac.in">
                        <img class="photo" src="https://aitmbgm.ac.in/wp-content/themes/aitmbgm-20/images/Suresh-Angadi.jpg" alt="AITMBGM" title="AITMBGM">
                    </a>
                </h2>
            </div>

            <div class="site_header_2">
                <h2 class="web_title">
                    <a class="back" href="https://aitmbgm.ac.in">
                        <img class="photo" src="https://aitmbgm.ac.in/wp-content/themes/aitmbgm-20/images/aitmbgm-logo.png" alt="AITMBGM" title="AITMBGM">
                    </a>
                </h2>
            </div>

            <div class="site_header_3">
                <h6>SURESH ANGADI EDUCATION FOUNDATIONS</h6>
                <h2>ANGADI INSTITUTE OF TECHNOLOGY AND MANAGEMENT</h2>
                <span>Approved by AICTE, New Delhi, Affiliated to VTU, Belagavi. <br>Accredited by *NBA and NAAC</span>
            </div>

            <div class="site_header_4">
                <img class="photo" src="https://aitmbgm.ac.in/wp-content/themes/aitmbgm-20/images/aitm-logo.png" alt="AITM" title="AITM">
            </div>
        </div>
    </div>

    <nav class="navbar">

    </nav>

    <div class="main-content">
        <div class="video-list" id="videoList">
            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "its_db";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                echo '<div id="debug">Connection failed: ' . $conn->connect_error . '</div>';
            } else {
                // Query to fetch only video_id and title
                $sql = "SELECT video_id, title FROM videos";
                $result = $conn->query($sql);

                if (!$result) {
                    echo '<div id="debug">Query failed: ' . $conn->error . '</div>';
                } else {
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="video-item" onclick="selectVideo(' . $row['video_id'] . ')">';
                            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div id="debug">No videos found in database</div>';
                    }
                }
                $conn->close();
            }
            ?>
        </div>
        <div class="video-player" id="videoPlayer">
            <div class="video-container">
                <div class="video-wrapper">
                    <video id="videoElement" controls preload="metadata">
                        Your browser does not support the video element.
                    </video>
                    <audio id="audioElement">
                        Your browser does not support the audio element.
                    </audio>
                </div>
                <div class="controls-wrapper">
                    <select class="language-selector" id="languageSelect">
                        <?php
                        try {
                            $stmt = $conn->query("SELECT lang_code, name FROM Languages");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['lang_code']}'>{$row['name']}</option>";
                            }
                        } catch(PDOException $e) {
                            echo "<option value=''>Error loading languages</option>";
                        }
                        ?>
                    </select>
                    <div id="progressBar">
                        <div id="progress"></div>
                    </div>
                    <button class="control-button" id="playPauseBtn">Play</button>
                    <span class="time-display" id="currentTime">00:00</span>
                    /
                    <span class="time-display" id="duration">00:00</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Remove this first function
        // function selectVideo(videoId) {
        //     console.log('Selected video:', videoId);
        //     document.getElementById('placeholder').textContent = 'Loading video ' + videoId + '...';
        // }
        
        const videoElement = document.getElementById('videoElement');
        const audioElement = document.getElementById('audioElement');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const progressBar = document.getElementById('progressBar');
        const progress = document.getElementById('progress');
        const currentTimeDisplay = document.getElementById('currentTime');
        const durationDisplay = document.getElementById('duration');
        
        async function selectVideo(videoId) {
            try {
                console.log('Loading video:', videoId);
                const response = await fetch(`get_video_data.php?video_id=${videoId}`);
                const data = await response.json();
                
                if (data.error) {
                    throw new Error(data.error);
                }
        
                // Convert absolute Windows path to web path
                const webPath = data.original_path  // Changed from video_path to original_path
                    .replace('C:\\xampp\\htdocs\\Intelligent-transportation-system-Website', '')
                    .replace(/\\/g, '/');
                
                console.log('Video web path:', webPath);
                videoElement.src = webPath;
                
                // Reset controls
                playPauseBtn.textContent = 'Play';
                progress.style.width = '0%';
                currentTimeDisplay.textContent = '00:00';
                
                // Load and play the video
                await videoElement.load();
                try {
                    await videoElement.play();
                    playPauseBtn.textContent = 'Pause';
                } catch (error) {
                    console.error('Error playing video:', error);
                    alert('Error playing video. Please check if the video file exists.');
                }
            } catch (error) {
                console.error('Error loading video:', error);
                alert('Error loading video. Please try again.');
            }
        }

        // Event Listeners
        playPauseBtn.addEventListener('click', () => {
            if (videoElement.paused) {
                videoElement.play();
                playPauseBtn.textContent = 'Pause';
            } else {
                videoElement.pause();
                playPauseBtn.textContent = 'Play';
            }
        });

        progressBar.addEventListener('click', (e) => {
            const pos = (e.pageX - progressBar.offsetLeft) / progressBar.offsetWidth;
            const time = pos * videoElement.duration;
            videoElement.currentTime = time;
        });

        videoElement.addEventListener('timeupdate', () => {
            const pos = (videoElement.currentTime / videoElement.duration) * 100;
            progress.style.width = `${pos}%`;
            currentTimeDisplay.textContent = formatTime(videoElement.currentTime);
        });

        videoElement.addEventListener('loadedmetadata', () => {
            durationDisplay.textContent = formatTime(videoElement.duration);
        });

        function formatTime(seconds) {
            if (isNaN(seconds)) return "00:00";
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
    </script>
</body>
</html>