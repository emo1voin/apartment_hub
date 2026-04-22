<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ApartmentHub - Бронирование квартир')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        [x-cloak] { display: none !important; }
        
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        
        /* Music Player Styles */
        .music-player {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .music-player-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .music-player-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
        }
        
        .music-player-btn.playing {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            }
            50% {
                box-shadow: 0 4px 25px rgba(59, 130, 246, 0.8);
            }
        }
        
        .volume-slider {
            position: absolute;
            bottom: 70px;
            right: 0;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 60px;
        }
        
        .volume-slider input[type="range"] {
            writing-mode: bt-lr;
            -webkit-appearance: slider-vertical;
            width: 30px;
            height: 100px;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('partials.header')
    
    <main>
        @if(session('success'))
            <div class="container mx-auto px-4 lg:px-8 max-w-7xl mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mx-auto px-4 lg:px-8 max-w-7xl mt-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    {{-- YouTube Music Player - TEMPORARILY DISABLED
    <div x-data="musicPlayer()" class="music-player">
        <!-- Volume Control -->
        <div x-show="showVolume" 
             x-cloak
             @click.away="showVolume = false"
             class="volume-slider">
            <input type="range" 
                   min="0" 
                   max="100" 
                   x-model="volume"
                   @input="changeVolume()"
                   class="w-full">
            <div class="text-center text-xs text-gray-600 mt-2" x-text="volume + '%'"></div>
        </div>
        
        <!-- Play/Pause Button -->
        <button @click="togglePlay()" 
                :class="{ 'playing': isPlaying }"
                class="music-player-btn"
                title="Фоновая музыка">
            <svg x-show="!isPlaying" class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
            </svg>
            <svg x-show="isPlaying" x-cloak class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 4a2 2 0 012-2h1a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V4zm7 0a2 2 0 012-2h1a2 2 0 012 2v12a2 2 0 01-2 2h-1a2 2 0 01-2-2V4z"/>
            </svg>
        </button>
        
        <!-- Volume Button -->
        <button @click="showVolume = !showVolume" 
                class="absolute -top-12 right-0 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
                title="Громкость">
            <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
    
    <!-- YouTube IFrame API -->
    <div id="youtube-player" style="display: none;"></div>
    
    <script>
        // Load YouTube IFrame API
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        
        var player;
        
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                height: '0',
                width: '0',
                videoId: '1HJBRFRoitQ',
                playerVars: {
                    'autoplay': 0,
                    'controls': 0,
                    'loop': 1,
                    'playlist': '1HJBRFRoitQ'
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }
        
        function onPlayerReady(event) {
            // Restore volume from localStorage
            const savedVolume = localStorage.getItem('musicVolume');
            if (savedVolume) {
                player.setVolume(parseInt(savedVolume));
            } else {
                player.setVolume(30); // Default volume
            }
        }
        
        function onPlayerStateChange(event) {
            // Auto-loop when video ends
            if (event.data === YT.PlayerState.ENDED) {
                player.playVideo();
            }
        }
        
        function musicPlayer() {
            return {
                isPlaying: false,
                showVolume: false,
                volume: localStorage.getItem('musicVolume') || 30,
                
                togglePlay() {
                    if (!player || !player.playVideo) {
                        console.log('Player not ready yet');
                        return;
                    }
                    
                    if (this.isPlaying) {
                        player.pauseVideo();
                        this.isPlaying = false;
                    } else {
                        player.playVideo();
                        this.isPlaying = true;
                    }
                },
                
                changeVolume() {
                    if (player && player.setVolume) {
                        player.setVolume(this.volume);
                        localStorage.setItem('musicVolume', this.volume);
                    }
                }
            }
        }
    </script>
    --}}
    
    @stack('scripts')
</body>
</html>
