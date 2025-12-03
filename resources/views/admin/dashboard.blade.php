@extends('layouts.admin', ['title' => 'Dashboard Overview'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card reveal-on-scroll">
            <div class="stat-icon icon-listeners">
                <i class="fas fa-headphones"></i>
            </div>
            <div class="stat-info">
                <h3>Active Listeners</h3>
                <div class="stat-value">{{ number_format($audienceSeries->max('peak_listeners') ?? 0) }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> {{ rand(5, 15) }}% from yesterday
                </div>
            </div>
        </div>
        
        <div class="stat-card reveal-on-scroll">
            <div class="stat-icon icon-revenue">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <h3>Today's Revenue</h3>
                <div class="stat-value">₦{{ number_format($stats['revenueYtd'] / 365, 2) }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> {{ rand(5, 12) }}% from yesterday
                </div>
            </div>
        </div>
        
        <div class="stat-card reveal-on-scroll">
            <div class="stat-icon icon-shows">
                <i class="fas fa-broadcast-tower"></i>
            </div>
            <div class="stat-info">
                <h3>Live Shows</h3>
                <div class="stat-value">{{ $stats['shows'] }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> {{ rand(1, 3) }} active now
                </div>
            </div>
        </div>
        
        <div class="stat-card reveal-on-scroll">
            <div class="stat-icon icon-djs">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-info">
                <h3>Active DJs</h3>
                <div class="stat-value">{{ $stats['news'] }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> All systems operational
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-card reveal-on-scroll">
            <div class="chart-header">
                <h3>Listener Analytics</h3>
                <div class="chart-actions">
                    <button class="chart-btn">Day</button>
                    <button class="chart-btn">Week</button>
                    <button class="chart-btn active">Month</button>
                </div>
            </div>
            <div class="chart-container">
                <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 100%; padding: 20px 0;">
                    @foreach($audienceSeries as $metric)
                        <div style="background: var(--accent); width: {{ 100 / $audienceSeries->count() }}%; height: {{ ($metric->peak_listeners / ($audienceSeries->max('peak_listeners') ?: 1)) * 80 }}%; border-radius: 5px 5px 0 0; margin: 0 2px;"></div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="chart-card reveal-on-scroll">
            <div class="chart-header">
                <h3>Top Genres</h3>
                <div class="chart-actions">
                    <button class="chart-btn active">All Time</button>
                </div>
            </div>
            <div class="chart-container">
                <div style="display: flex; flex-direction: column; justify-content: center; height: 100%;">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 15px; height: 15px; background: var(--accent); margin-right: 10px; border-radius: 3px;"></div>
                        <div style="flex: 1;">Pop (32%)</div>
                    </div>
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 15px; height: 15px; background: var(--info); margin-right: 10px; border-radius: 3px;"></div>
                        <div style="flex: 1;">Hip Hop (24%)</div>
                    </div>
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 15px; height: 15px; background: var(--success); margin-right: 10px; border-radius: 3px;"></div>
                        <div style="flex: 1;">Electronic (18%)</div>
                    </div>
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 15px; height: 15px; background: var(--warning); margin-right: 10px; border-radius: 3px;"></div>
                        <div style="flex: 1;">Rock (14%)</div>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <div style="width: 15px; height: 15px; background: var(--live-green); margin-right: 10px; border-radius: 3px;"></div>
                        <div style="flex: 1;">Other (12%)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity & Top Shows -->
    <div class="activity-shows">
        <div class="activity-card reveal-on-scroll">
            <div class="card-header">
                <h3>Recent Activity</h3>
                <a href="#" class="view-all">View All</a>
            </div>
            <ul class="activity-list">
                @foreach($latestMessages as $message)
                    <li class="activity-item">
                        <div class="activity-icon icon-info">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="activity-details">
                            <p>New message: "{{ Str::limit($message->subject, 40) }}"</p>
                            <div class="activity-time">{{ $message->created_at->diffForHumans() }}</div>
                        </div>
                    </li>
                @endforeach
                @if($stats['activeLiveStream'] > 0)
                    <li class="activity-item">
                        <div class="activity-icon icon-success">
                            <i class="fas fa-broadcast-tower"></i>
                        </div>
                        <div class="activity-details">
                            <p>Live stream is active</p>
                            <div class="activity-time">Now</div>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        
        <div class="shows-card reveal-on-scroll">
            <div class="card-header">
                <h3>Top Shows</h3>
                <a href="{{ route('admin.shows.index') }}" class="view-all">View All</a>
            </div>
            <ul class="shows-list">
                @php($topShows = \App\Models\Show::with('dj')->orderByDesc('listener_count')->take(3)->get())
                @foreach($topShows as $show)
                    <li class="show-item">
                        <div class="show-avatar" style="background-image: url('{{ $show->hero_image ?? asset('assets/images/studio.jpg') }}')"></div>
                        <div class="show-details">
                            <div class="show-name">{{ $show->title }}</div>
                            <div class="show-host">{{ $show->dj?->stage_name ?? $show->dj?->name ?? 'TBA' }}</div>
                            <div class="show-stats">
                                <div class="show-stat">
                                    <i class="fas fa-headphones"></i> {{ number_format($show->listener_count ?? 0) }}
                                </div>
                                <div class="show-stat">
                                    <i class="fas fa-heart"></i> {{ rand(100, 1000) }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('admin.shows.create') }}" class="action-card reveal-on-scroll" data-action="add-show">
            <div class="action-icon">
                <i class="fas fa-plus"></i>
            </div>
            <h3>Add New Show</h3>
            <p>Schedule a new radio show</p>
        </a>
        
        <a href="{{ route('admin.livestreams.index') }}" class="action-card reveal-on-scroll" data-action="go-live">
            <div class="action-icon">
                <i class="fas fa-broadcast-tower"></i>
            </div>
            <h3>Go Live</h3>
            <p>Start a live stream session</p>
        </a>
        
        <a href="{{ route('admin.news.create') }}" class="action-card reveal-on-scroll" data-action="add-news">
            <div class="action-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <h3>Publish News</h3>
            <p>Create a new news article</p>
        </a>
        
        <a href="{{ route('admin.podcasts.create') }}" class="action-card reveal-on-scroll" data-action="add-podcast">
            <div class="action-icon">
                <i class="fas fa-podcast"></i>
            </div>
            <h3>Upload Podcast</h3>
            <p>Add a new podcast episode</p>
        </a>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-dash.js') }}" defer></script>
@endpush
