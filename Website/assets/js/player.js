document.addEventListener('DOMContentLoaded', function() {
    const videoElement = document.getElementById('videoElement');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    const currentTimeDisplay = document.getElementById('currentTime');
    const durationDisplay = document.getElementById('duration');
    const transcriptionText = document.getElementById('transcriptionText');
    const languageSelect = document.getElementById('languageSelect');

    let currentVideoId = null;

    // --- Select and load a video ---
    window.selectVideo = async function(videoId) {
        try {
            currentVideoId = videoId;
            console.log('Loading video:', videoId);

            const response = await fetch('api/get_video_data.php?video_id=' + videoId);

            // Debug: Log raw response
            const responseText = await response.text();
            console.log('Raw API Response:', responseText);

            // Try to parse the response
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                throw new Error('Invalid response from server');
            }

            if (data.error) {
                throw new Error(data.error);
            }

            console.log('Video data:', data);

            // Debug: Log the full video path
            console.log('Setting video source to:', data.original_path);

            // Set video source, encode spaces
            videoElement.src = data.original_path.replace(/ /g, "%20");

            // Reset controls
            playPauseBtn.textContent = 'Play';
            progress.style.width = '0%';
            currentTimeDisplay.textContent = '00:00';
            durationDisplay.textContent = '00:00';

            // Always load English transcription by default
            if (languageSelect) {
                languageSelect.value = "en";
                loadTranscription(videoId, "en");
            } else {
                loadTranscription(videoId, "en");
            }

            await videoElement.load();
            try {
                await videoElement.play();
                playPauseBtn.textContent = 'Pause';
            } catch (playError) {
                console.error('Play error:', playError);
                if (data.debug_info) {
                    console.log('Debug info:', data.debug_info);
                }
                alert('Error playing video. Check browser console for details.');
            }
        } catch (error) {
            console.error('Error loading video:', error);
            alert('Error loading video: ' + error.message);
        }
    };

    // --- Load transcription ---
    async function loadTranscription(videoId, langCode) {
        try {
            const response = await fetch(`api/get_transcription.php?video_id=${videoId}&lang_code=${langCode}`);
            const data = await response.json();

            if (data.error) {
                transcriptionText.innerHTML = 'Transcription not available.';
                return;
            }

            transcriptionText.innerHTML = data.transcript;
        } catch (error) {
            console.error('Error loading transcription:', error);
            transcriptionText.innerHTML = 'Error loading transcription.';
        }
    }

    // --- Change language (for current video) ---
    window.changeLanguage = function(langCode) {
        if (currentVideoId) {
            loadTranscription(currentVideoId, langCode);
        }
    };

    // --- Video player controls ---
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
        const rect = progressBar.getBoundingClientRect();
        const pos = (e.clientX - rect.left) / rect.width;
        videoElement.currentTime = pos * videoElement.duration;
    });

    videoElement.addEventListener('timeupdate', () => {
        const pos = (videoElement.currentTime / videoElement.duration) * 100;
        progress.style.width = `${pos}%`;
        currentTimeDisplay.textContent = formatTime(videoElement.currentTime);
    });

    videoElement.addEventListener('loadedmetadata', () => {
        durationDisplay.textContent = formatTime(videoElement.duration);
    });

    videoElement.addEventListener('error', (e) => {
        console.error('Video error:', {
            error: videoElement.error,
            currentSrc: videoElement.src,
            networkState: videoElement.networkState,
            readyState: videoElement.readyState
        });
    });

    // --- Auto-select and load the first video on page load ---
    const firstVideoLink = document.querySelector('#videosList a');
    if (firstVideoLink) firstVideoLink.click();
});

function formatTime(seconds) {
    if (isNaN(seconds)) return "00:00";
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}