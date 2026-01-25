@extends('layouts.frontend', ['title' => 'Darling FM • Live Stream'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/live-stream.css') }}">
@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Radio Player -->
            <div class="radio-player">
                <div class="player-controls">
                    <button class="play-btn" id="playButton">
                        <i class="fas fa-play"></i>
                    </button>
                </div>
                <div class="player-info">
                    <div class="player-title" id="currentStream">Main Radio Stream</div>
                    <div class="player-subtitle" id="streamStatus">Click play to start listening</div>
                </div>
                <div class="stream-selector">
                    <div class="stream-option active" data-stream="main">Main Stream</div>
                    <div class="stream-option" data-stream="oap">OAP Live</div>
                    <div class="stream-option" data-stream="backup">Backup</div>
                </div>
            </div>
            
            <!-- Stream Hero Section -->
            <div class="stream-hero">
                <div class="video-container">
                    <div class="video-placeholder">
                        <i class="fas fa-broadcast-tower"></i>
                        <h3>Live Stream Starting Soon</h3>
                        @if($liveStream)
                            <p>Current show: {{ $liveStream->show?->title ?? 'Live Session' }}</p>
                        @else
                            <p>Next show: TBA</p>
                        @endif
                        <div class="live-indicator" style="margin-top: 20px;">LIVE</div>
                    </div>
                </div>
                
                <div class="stream-stats">
                    <div class="stat-card">
                        <h3>Current Listeners</h3>
                        <div class="stat-value">{{ number_format($liveStream->listener_count ?? 0) }}</div>
                        <div class="listeners-list">
                            @if($liveStream && $liveStream->listener_count > 0)
                                @for($i = 0; $i < min(4, $liveStream->listener_count); $i++)
                                    <div class="listener-item">
                                        <div class="listener-avatar">{{ strtoupper(substr('Listener' . $i, 0, 2)) }}</div>
                                        <span>Listener{{ $i + 1 }}</span>
                                    </div>
                                @endfor
                            @else
                                <div class="listener-item">
                                    <div class="listener-avatar">--</div>
                                    <span>No active listeners</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Stream Stats</h3>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Uptime:</span>
                            <span>@if($liveStream && $liveStream->started_at){{ $liveStream->started_at->diffForHumans() }}@else N/A @endif</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Peak Listeners:</span>
                            <span>{{ number_format($liveStream->listener_count ?? 0) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Status:</span>
                            <span>{{ strtoupper($liveStream->status ?? 'offline') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stream Controls -->
            <div class="stream-controls">
                <button class="control-btn" id="voiceChatBtn">
                    <i class="fas fa-microphone"></i>
                    <span>Voice Chat</span>
                </button>
                <button class="control-btn" id="requestSongBtn">
                    <i class="fas fa-music"></i>
                    <span>Request Song</span>
                </button>
                <button class="control-btn" id="livePollBtn">
                    <i class="fas fa-poll"></i>
                    <span>Live Poll</span>
                </button>
                <button class="control-btn" id="shareStreamBtn">
                    <i class="fas fa-share-alt"></i>
                    <span>Share Stream</span>
                </button>
            </div>
            
            <!-- Interactive Section -->
            <div class="interactive-section">
                <!-- Chat -->
                <div class="chat-container">
                    <div class="chat-header">
                        <h3>Live Chat</h3>
                        <span>{{ number_format($liveStream->listener_count ?? 0) }} listeners</span>
                    </div>
                    
                    <div class="chat-messages" id="chatMessages">
                        <div class="message">
                            <div class="message-avatar">DM</div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-author">DarlingFM</span>
                                    <span class="message-time">{{ now()->format('g:i A') }}</span>
                                </div>
                                <p class="message-text">Welcome to Darling FM Live Stream! Enjoy the music!</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-input">
                        <input type="text" placeholder="Type your message..." id="chatInput">
                        <button id="chatSend"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
                
                <!-- Interactive Widgets -->
                <div class="interactive-widgets">
                    <div class="widget">
                        <h3>Song Requests</h3>
                        <div class="song-requests">
                            <div class="request-item">
                                <div class="request-info">
                                    <div class="request-song">Request your favorite song</div>
                                    <div class="request-user">Click "Request Song" button</div>
                                </div>
                                <div class="request-actions">
                                    <button class="action-btn"><i class="fas fa-check"></i></button>
                                    <button class="action-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget">
                        <h3>Live Poll</h3>
                        <p>Which genre should we play next?</p>
                        <div class="poll-options">
                            <div class="poll-option">
                                <span>Drum & Bass</span>
                                <span class="poll-percentage">42%</span>
                            </div>
                            <div class="poll-option active">
                                <span>House</span>
                                <span class="poll-percentage">35%</span>
                            </div>
                            <div class="poll-option">
                                <span>Dubstep</span>
                                <span class="poll-percentage">23%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Visualizer -->
            <div class="visualizer-container">
                <h3>Audio Visualizer</h3>
                <div class="visualizer" id="visualizer">
                    <!-- Bars will be generated by JavaScript -->
                </div>
            </div>

            <!-- Show Lineup -->
            <section class="container" style="margin-top: 40px;">
                <h2 class="section-title">Show Lineup</h2>
                <div class="schedule-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Show</th>
                                <th>Host</th>
                                <th>Day</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shows as $show)
                                <tr>
                                    <td><a href="{{ route('shows.show', $show) }}" wire:navigate>{{ $show->title }}</a></td>
                                    <td>{{ $show->dj?->stage_name ?? $show->dj?->name }}</td>
                                    <td>{{ $show->day_of_week }}</td>
                                    <td>{{ $show->start_time }} - {{ $show->end_time }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal" id="voiceChatModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Voice Chat</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Join the live voice chat with other listeners and the DJ!</p>
                <div class="form-group">
                    <label for="voiceName">Your Display Name</label>
                    <input type="text" id="voiceName" class="form-control" placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label for="voiceDevice">Audio Device</label>
                    <select id="voiceDevice" class="form-control">
                        <option value="default">Default Microphone</option>
                        <option value="headset">Headset Microphone</option>
                        <option value="usb">USB Microphone</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="voiceAgree">
                        I agree to follow community guidelines
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline">Cancel</button>
                <button class="btn btn-primary">Join Voice Chat</button>
            </div>
        </div>
    </div>

    <div class="modal" id="requestSongModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Request a Song</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Request your favorite song to be played on air!</p>
                <div class="form-group">
                    <label for="songTitle">Song Title</label>
                    <input type="text" id="songTitle" class="form-control" placeholder="Enter song title">
                </div>
                <div class="form-group">
                    <label for="songArtist">Artist</label>
                    <input type="text" id="songArtist" class="form-control" placeholder="Enter artist name">
                </div>
                <div class="form-group">
                    <label for="songMessage">Message to DJ (optional)</label>
                    <textarea id="songMessage" class="form-control" placeholder="Add a message for the DJ"></textarea>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="songAgree">
                        I confirm this song follows community guidelines
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline">Cancel</button>
                <button class="btn btn-primary">Submit Request</button>
            </div>
        </div>
    </div>

    <div class="modal" id="livePollModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Live Poll</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Vote in the current live poll!</p>
                <h4>Which music decade do you prefer?</h4>
                <div class="poll-options">
                    <div class="poll-option">
                        <span>80s</span>
                        <span class="poll-percentage">25%</span>
                    </div>
                    <div class="poll-option">
                        <span>90s</span>
                        <span class="poll-percentage">35%</span>
                    </div>
                    <div class="poll-option">
                        <span>2000s</span>
                        <span class="poll-percentage">20%</span>
                    </div>
                    <div class="poll-option">
                        <span>2010s+</span>
                        <span class="poll-percentage">20%</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline">Close</button>
            </div>
        </div>
    </div>

    <div class="modal" id="shareStreamModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Share Stream</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Share this live stream with your friends!</p>
                <div class="form-group">
                    <label for="shareUrl">Stream URL</label>
                    <input type="text" id="shareUrl" class="form-control" value="{{ route('live', absolute: true) }}" readonly>
                    <button class="btn btn-outline" style="margin-top: 10px; width: 100%;" id="copyUrlBtn">
                        <i class="fas fa-copy"></i> Copy URL
                    </button>
                </div>
                <div class="form-group">
                    <label>Share to:</label>
                    <div class="social-share-buttons">
                        <button class="social-share-btn facebook" data-platform="facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                        <button class="social-share-btn twitter" data-platform="twitter">
                            <i class="fab fa-twitter"></i> Twitter
                        </button>
                        <button class="social-share-btn whatsapp" data-platform="whatsapp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline">Close</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/live-stream.js') }}" defer></script>
@endpush
