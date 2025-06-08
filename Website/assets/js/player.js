// Global variables
let currentVideoId = null;
let transcriptionText = null;
let currentDescription = '';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DOM elements
    const videoElement = document.getElementById('videoElement');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    const currentTimeDisplay = document.getElementById('currentTime');
    const durationDisplay = document.getElementById('duration');
    
    // Get content elements
    transcriptionText = document.getElementById('transcriptionText');
    const descriptionText = document.getElementById('descriptionText');
    
    // Get toggle buttons
    const showTranscriptionBtn = document.getElementById('showTranscription');
    const showDescriptionBtn = document.getElementById('showDescription');

    // Verify elements exist
    if (!transcriptionText || !descriptionText) {
        console.error('Content elements not found!');
    }

    if (!showTranscriptionBtn || !showDescriptionBtn) {
        console.error('Toggle buttons not found!');
    }

    // Add toggle button event listeners
    if (showTranscriptionBtn && showDescriptionBtn) {
        showTranscriptionBtn.addEventListener('click', () => {
            console.log('Showing transcription');
            showTranscriptionBtn.classList.add('active');
            showDescriptionBtn.classList.remove('active');
            transcriptionText.classList.add('active');
            transcriptionText.style.display = 'block';
            descriptionText.classList.remove('active');
            descriptionText.style.display = 'none';
        });

        showDescriptionBtn.addEventListener('click', () => {
            console.log('Showing description');
            showDescriptionBtn.classList.add('active');
            showTranscriptionBtn.classList.remove('active');
            descriptionText.classList.add('active');
            descriptionText.style.display = 'block';
            transcriptionText.classList.remove('active');
            transcriptionText.style.display = 'none';
        });
    }

    // Update your selectVideo function to handle description
    window.selectVideo = async function(videoId) {
        try {
            currentVideoId = videoId;
            console.log('Loading video:', videoId);
            
            const response = await fetch('api/get_video_data.php?video_id=' + videoId);
            const responseText = await response.text();
            console.log('Raw API Response:', responseText);
            
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
            
            // Set video source
            videoElement.src = data.original_path.replace(/ /g, "%20");
            
            // Reset controls
            playPauseBtn.textContent = 'Play';
            progress.style.width = '0%';
            currentTimeDisplay.textContent = '00:00';
            
            // Update description content
            if (data.description) {
                console.log('Setting description:', data.description);
                descriptionText.innerHTML = data.description;
                currentDescription = data.description;
            } else {
                console.log('No description available');
                descriptionText.innerHTML = 'No description available for this video.';
                currentDescription = '';
            }
            
            // Load transcription
            const languageSelect = document.getElementById('languageSelect');
            if (languageSelect) {
                console.log('Loading transcription for video:', videoId, 'language:', languageSelect.value);
                await loadTranscription(videoId, languageSelect.value);
            }
            
            await videoElement.load();
            
        } catch (error) {
            console.error('Error loading video:', error);
            alert('Error loading video: ' + error.message);
        }
    };

    // Video player controls
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

    // Auto-select first video
    const firstVideoLink = document.querySelector('#videosList a');
    if (firstVideoLink) {
        firstVideoLink.click();
    }
});

// Transcription loading function
async function loadTranscription(videoId, langCode) {
    if (!transcriptionText) {
        console.error('Transcription text element not found in loadTranscription');
        return;
    }

    try {
        const url = `api/get_transcription.php?video_id=${videoId}&lang_code=${langCode}`;
        console.log('Fetching transcription:', url);
        
        const response = await fetch(url);
        
        // Log raw response for debugging
        const responseText = await response.text();
        console.log('Raw transcription response:', responseText);
        
        // Parse the response
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse transcription response:', e);
            transcriptionText.innerHTML = 'Error: Invalid response format';
            return;
        }
        
        if (data.error) {
            console.error('Transcription error:', data.error);
            transcriptionText.innerHTML = `Transcription not available: ${data.error}`;
            return;
        }
        
        if (!data.transcript) {
            console.error('No transcript in response:', data);
            transcriptionText.innerHTML = 'Error: No transcript content';
            return;
        }
        
        console.log('Transcription loaded successfully');
        transcriptionText.innerHTML = data.transcript;
    } catch (error) {
        console.error('Error loading transcription:', error);
        transcriptionText.innerHTML = 'Error loading transcription: ' + error.message;
    }
}

// Language change handler
window.changeLanguage = function(langCode) {
    console.log('Language changed to:', langCode);
    if (currentVideoId) {
        loadTranscription(currentVideoId, langCode);
    } else {
        console.warn('No video selected when changing language');
    }
};

// Time formatting helper
function formatTime(seconds) {
    if (isNaN(seconds)) return "00:00";
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}