// Video Player Script
// Global variables
console.log("Script starting to load...");

let currentVideoId = null;
let transcriptionText = document.getElementById('transcriptionText');
let descriptionText = document.getElementById('descriptionText');
let currentDescription = '';
let currentAudioElement = null;
let isAudioLoaded = false;

console.log("Initial element check:", {
    transcriptionText: transcriptionText,
    descriptionText: descriptionText
});

document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM Content Loaded event fired");
    
    // Initialize DOM elements
    const videoElement = document.getElementById('videoElement');
    const audioElement = document.createElement('audio');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    const currentTimeDisplay = document.getElementById('currentTime');
    const durationDisplay = document.getElementById('duration');
    const volumeControl = document.getElementById('volumeControl');
    const languageSelect = document.getElementById('languageSelect');
    const showTranscriptionBtn = document.getElementById('showTranscription');
    const showDescriptionBtn = document.getElementById('showDescription');
    const showFeedbackBtn = document.getElementById('showFeedback');
    const feedbackForm = document.getElementById('feedbackForm');
    
    console.log("DOM elements check:", {
        videoElement,
        showTranscriptionBtn,
        showDescriptionBtn,
        showFeedbackBtn,
        feedbackForm
    });

    // Re-initialize transcription elements
    transcriptionText = document.getElementById('transcriptionText');
    descriptionText = document.getElementById('descriptionText');
    
    console.log("Re-initialized transcription elements:", {
        transcriptionText,
        descriptionText
    });

    // Debug info element
    const debugInfo = document.createElement('div');
    debugInfo.id = 'debugInfo';
    debugInfo.style.display = 'none';
    debugInfo.innerHTML = `
        <p>Current Video ID: <span id="currentVideoIdDisplay"></span></p>
        <p>Current Language: <span id="currentLanguageDisplay"></span></p>
        <p>Audio Loaded: <span id="audioLoadedDisplay"></span></p>
        <p>Last Updated: ${new Date().toISOString()}</p>
    `;
    document.body.appendChild(debugInfo);
    
    // Audio setup
    audioElement.id = 'audioElement';
    currentAudioElement = audioElement;
    document.body.appendChild(audioElement);

    // Add language change listener
    if (languageSelect) {
        languageSelect.addEventListener('change', async (e) => {
            console.log('Language select changed:', e.target.value);
            await changeLanguage(e.target.value);
        });
    }

    // Add toggle functionality with direct click handlers
    if (showTranscriptionBtn && showDescriptionBtn && showFeedbackBtn) {
        console.log("Initializing toggle buttons");
        
        // Remove any existing event listeners
        showTranscriptionBtn.replaceWith(showTranscriptionBtn.cloneNode(true));
        showDescriptionBtn.replaceWith(showDescriptionBtn.cloneNode(true));
        showFeedbackBtn.replaceWith(showFeedbackBtn.cloneNode(true));
        
        // Get fresh references after cloning
        const freshTranscriptionBtn = document.getElementById('showTranscription');
        const freshDescriptionBtn = document.getElementById('showDescription');
        const freshFeedbackBtn = document.getElementById('showFeedback');
        
        console.log("Fresh button references:", {
            transcription: freshTranscriptionBtn,
            description: freshDescriptionBtn,
            feedback: freshFeedbackBtn
        });

        // Function to update content visibility
        function updateContentVisibility(activeSection) {
            if (transcriptionText && descriptionText && feedbackForm) {
                // Hide all sections
                transcriptionText.classList.remove('active');
                descriptionText.classList.remove('active');
                feedbackForm.classList.remove('active');
                
                // Remove active class from all buttons
                freshTranscriptionBtn.classList.remove('active');
                freshDescriptionBtn.classList.remove('active');
                freshFeedbackBtn.classList.remove('active');
                
                // Show selected section and activate button
                switch(activeSection) {
                    case 'transcription':
                        transcriptionText.classList.add('active');
                        freshTranscriptionBtn.classList.add('active');
                        console.log("Showing transcription");
                        break;
                    case 'description':
                        descriptionText.classList.add('active');
                        freshDescriptionBtn.classList.add('active');
                        console.log("Showing description:", descriptionText.innerHTML);
                        break;
                    case 'feedback':
                        feedbackForm.classList.add('active');
                        freshFeedbackBtn.classList.add('active');
                        console.log("Showing feedback form");
                        break;
                }
            } else {
                console.error("Content elements not found");
            }
        }

        // Add click handlers
        freshTranscriptionBtn.onclick = function(e) {
            console.log("Transcription button clicked");
            e.preventDefault();
            updateContentVisibility('transcription');
        };

        freshDescriptionBtn.onclick = function(e) {
            console.log("Description button clicked");
            e.preventDefault();
            updateContentVisibility('description');
        };

        freshFeedbackBtn.onclick = function(e) {
            console.log("Feedback button clicked");
            e.preventDefault();
            updateContentVisibility('feedback');
        };

        // Add visual feedback for button states
        [freshTranscriptionBtn, freshDescriptionBtn, freshFeedbackBtn].forEach(btn => {
            btn.style.cursor = 'pointer';
            btn.addEventListener('mouseover', () => {
                console.log(`${btn.id} hover`);
                btn.style.opacity = '0.8';
            });
            btn.addEventListener('mouseout', () => {
                btn.style.opacity = '1';
            });
        });

        // Handle feedback form submission
        if (feedbackForm) {
            feedbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log("Feedback form submitted");

                const formData = new FormData(feedbackForm);

                fetch('submit_feedback.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        feedbackForm.reset();
                        updateContentVisibility('transcription'); // Switch back to transcription
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error submitting feedback:', error);
                    alert('Error submitting feedback. Please try again.');
                });
            });
        }

        // Initialize with description visible
        updateContentVisibility('description');
    } else {
        console.error("Toggle buttons not found in DOM");
    }

    // Update debug info function
    function updateDebugInfo() {
        const debugInfoElement = document.getElementById('debugInfo');
        if (debugInfoElement) {
            document.getElementById('currentVideoIdDisplay').textContent = currentVideoId;
            document.getElementById('currentLanguageDisplay').textContent = 
                languageSelect?.value || 'none';
            document.getElementById('audioLoadedDisplay').textContent = isAudioLoaded;
        }
    }

    // Select video function
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
            videoElement.src = data.original_path;
            videoElement.load(); // Force reload of video element
            
            // Load audio for current language
            if (languageSelect) {
                await loadAudioForLanguage(videoId, languageSelect.value);
            }
            
            // Reset controls
            playPauseBtn.textContent = 'Play';
            progress.style.width = '0%';
            currentTimeDisplay.textContent = '00:00';
            
            // Update description and transcription
            if (data.description) {
                console.log('Setting description:', data.description);
                descriptionText.innerHTML = data.description;
                currentDescription = data.description;
            } else {
                console.log('No description available');
                descriptionText.innerHTML = 'No description available for this video.';
            }
            
            if (languageSelect) {
                await loadTranscription(videoId, languageSelect.value);
            }
            
            await videoElement.load();
            updateDebugInfo();
            
        } catch (error) {
            console.error('Error loading video:', error);
            alert('Error loading video: ' + error.message);
        }
    };

    // Load audio for language function
    async function loadAudioForLanguage(videoId, langCode) {
        console.log('loadAudioForLanguage called with:', { videoId, langCode });
        
        try {
            const url = `api/get_audio_translation.php?video_id=${videoId}&lang_code=${langCode}`;
            console.log('Fetching audio from:', url);
            
            const response = await fetch(url);
            const responseText = await response.text();
            console.log('Raw audio response:', responseText);
            
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse audio response:', e);
                isAudioLoaded = false;
                return;
            }
            
            if (data.error) {
                console.error('Audio loading error:', data.error);
                isAudioLoaded = false;
                return;
            }
            
            if (data.audio_path) {
                console.log('Got audio path:', data.audio_path);
                const wasPlaying = !videoElement.paused;
                const currentTime = videoElement.currentTime;
                
                // Handle relative paths
                currentAudioElement.src = data.audio_path;
                isAudioLoaded = true;
                
                console.log('Loading new audio...');
                await currentAudioElement.load();
                
                currentAudioElement.currentTime = currentTime;
                console.log('Set audio time to:', currentTime);
                
                if (wasPlaying) {
                    try {
                        console.log('Resuming audio playback...');
                        await currentAudioElement.play();
                    } catch (e) {
                        console.error('Error resuming audio playback:', e);
                    }
                }
            } else {
                console.warn('No audio translation available for language:', langCode);
                isAudioLoaded = false;
                currentAudioElement.removeAttribute('src');
            }
        } catch (error) {
            console.error('Error loading audio translation:', error);
            isAudioLoaded = false;
        }
        
        updateDebugInfo();
    }

    // Language change handler
    window.changeLanguage = async function(langCode) {
        console.log('changeLanguage called with:', langCode);
        console.log('Current video ID:', currentVideoId);
        
        if (!currentVideoId) {
            console.warn('No video selected when changing language');
            return;
        }
        
        try {
            console.log('Calling loadAudioForLanguage...');
            await loadAudioForLanguage(currentVideoId, langCode);
            
            console.log('Calling loadTranscription...');
            await loadTranscription(currentVideoId, langCode);
            
            updateDebugInfo();
        } catch (error) {
            console.error('Error changing language:', error);
        }
    };

    // Video and audio controls
    playPauseBtn.addEventListener('click', () => {
        if (videoElement.paused) {
            const playPromises = [videoElement.play()];
            if (isAudioLoaded) {
                playPromises.push(currentAudioElement.play());
            }
            Promise.all(playPromises)
                .then(() => playPauseBtn.textContent = 'Pause')
                .catch(error => console.error('Error playing media:', error));
        } else {
            videoElement.pause();
            if (isAudioLoaded) {
                currentAudioElement.pause();
            }
            playPauseBtn.textContent = 'Play';
        }
    });

    // Volume control
    if (volumeControl) {
        volumeControl.addEventListener('input', (e) => {
            const volume = e.target.value;
            videoElement.volume = volume;
            if (currentAudioElement) {
                currentAudioElement.volume = volume;
            }
        });
    }

    // Speed control
    const speedControl = document.getElementById('speedControl');
    if (speedControl) {
        speedControl.addEventListener('change', (e) => {
            const speed = parseFloat(e.target.value);
            videoElement.playbackRate = speed;
            if (currentAudioElement) {
                currentAudioElement.playbackRate = speed;
            }
        });
    }

    // Progress bar control
    progressBar.addEventListener('click', (e) => {
        const rect = progressBar.getBoundingClientRect();
        const pos = (e.clientX - rect.left) / rect.width;
        const newTime = pos * videoElement.duration;
        videoElement.currentTime = newTime;
        if (isAudioLoaded) {
            currentAudioElement.currentTime = newTime;
        }
    });

    // Keep audio and video in sync
    videoElement.addEventListener('timeupdate', () => {
        const pos = (videoElement.currentTime / videoElement.duration) * 100;
        progress.style.width = `${pos}%`;
        currentTimeDisplay.textContent = formatTime(videoElement.currentTime);
        
        if (isAudioLoaded && Math.abs(currentAudioElement.currentTime - videoElement.currentTime) > 0.1) {
            currentAudioElement.currentTime = videoElement.currentTime;
        }
    });

    videoElement.addEventListener('seeking', () => {
        if (isAudioLoaded) {
            currentAudioElement.currentTime = videoElement.currentTime;
        }
    });

    // Duration display
    videoElement.addEventListener('loadedmetadata', () => {
        durationDisplay.textContent = formatTime(videoElement.duration);
    });

    // Error handling
    videoElement.addEventListener('error', (e) => {
        console.error('Video error:', {
            error: videoElement.error,
            currentSrc: videoElement.src,
            networkState: videoElement.networkState,
            readyState: videoElement.readyState
        });
    });

    // Add ended event listener to play next video
    videoElement.addEventListener('ended', () => {
        const currentVideoLink = document.querySelector(`#videosList a[onclick*="selectVideo(${currentVideoId})"]`);
        if (currentVideoLink) {
            const nextVideoLink = currentVideoLink.parentElement.nextElementSibling?.querySelector('a');
            if (nextVideoLink) {
                nextVideoLink.click();
            }
        }
    });

    currentAudioElement.addEventListener('error', (e) => {
        console.error('Audio error:', {
            error: currentAudioElement.error,
            currentSrc: currentAudioElement.src,
            networkState: currentAudioElement.networkState,
            readyState: currentAudioElement.readyState
        });
    });

    // Auto-select first video if available
    const firstVideoLink = document.querySelector('#videosList a');
    if (firstVideoLink) {
        firstVideoLink.click();
    }
});

// Load transcription function
async function loadTranscription(videoId, langCode) {
    if (!transcriptionText) {
        console.error('Transcription text element not found in loadTranscription');
        return;
    }

    try {
        const url = `api/get_transcription.php?video_id=${videoId}&lang_code=${langCode}`;
        console.log('Fetching transcription:', url);
        
        const response = await fetch(url);
        const responseText = await response.text();
        console.log('Raw transcription response:', responseText);
        
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

// Time formatting helper function
function formatTime(seconds) {
    if (isNaN(seconds)) return "00:00";
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}


