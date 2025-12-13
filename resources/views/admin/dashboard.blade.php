@extends('layouts.admin', ['title' => 'Dashboard Overview'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    {{-- Stats are now displayed via Filament widgets (ListenerCountWidget, DashboardStatsWidget) --}}
    {{-- Removed duplicate cards to avoid redundancy --}}
    
    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-card reveal-on-scroll">
            <div class="chart-header">
                <h3>Listener Analytics</h3>
                <div class="chart-actions">
                    <button class="chart-btn" data-period="day" onclick="updateListenerChart('day')">Day</button>
                    <button class="chart-btn" data-period="week" onclick="updateListenerChart('week')">Week</button>
                    <button class="chart-btn active" data-period="month" onclick="updateListenerChart('month')">Month</button>
                    <button class="chart-btn" data-period="year" onclick="updateListenerChart('year')">Year</button>
                </div>
            </div>
            <div id="listenerStats" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 10px;" class="listener-stats-grid">
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent);" id="dayCount">{{ number_format($dailyListeners) }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Daily Sessions</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent);" id="weekCount">{{ number_format($weeklyListeners) }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Weekly Sessions</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent);" id="monthCount">{{ number_format($monthlyListenersTotal) }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Monthly Sessions</div>
                </div>
                <div style="text-align: center;">
                    @php
                        $yearlyTotal = \App\Models\AudienceMetric::whereYear('captured_for', now()->year)->sum('total_listening_sessions') ?? 0;
                    @endphp
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent);" id="yearCount">{{ number_format($yearlyTotal) }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Yearly Sessions</div>
                </div>
            </div>
            <div class="chart-container" id="listenerChartContainer" style="height: 300px; padding: 20px; background: rgba(0,0,0,0.2); border-radius: 10px;">
                @if($audienceSeries->count() > 0)
                <div id="chartBars" style="display: flex; align-items: flex-end; justify-content: space-between; height: 100%; gap: 5px;">
                    @php($maxListeners = $audienceSeries->max('peak_listeners') ?: 1)
                    @foreach($audienceSeries as $metric)
                        <div class="chart-bar" style="background: linear-gradient(to top, var(--accent), var(--accent-glow)); width: {{ 100 / $audienceSeries->count() }}%; height: {{ ($metric->peak_listeners / $maxListeners) * 100 }}%; border-radius: 5px 5px 0 0; min-height: 10px; position: relative; transition: all 0.3s; cursor: pointer;" 
                             data-value="{{ $metric->peak_listeners }}" 
                             data-date="{{ $metric->captured_for->format('M d') }}"
                             onmouseover="this.querySelector('.chart-tooltip').style.opacity='1'" 
                             onmouseout="this.querySelector('.chart-tooltip').style.opacity='0'">
                            <div style="position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.9); color: white; padding: 6px 10px; border-radius: 5px; font-size: 0.75rem; white-space: nowrap; margin-bottom: 5px; opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 10;" class="chart-tooltip">
                                <div style="font-weight: 700;">{{ number_format($metric->peak_listeners) }}</div>
                                <div style="font-size: 0.7rem; opacity: 0.8;">{{ $metric->captured_for->format('M d, Y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-secondary);">
                    <div style="text-align: center;">
                        <i class="fas fa-chart-bar" style="font-size: 3rem; opacity: 0.3; margin-bottom: 15px;"></i>
                        <p>No listener data available yet</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
    </div>
    
    <!-- Recent Activity & Top Shows -->
    <div class="activity-shows" style="margin-top: 60px; padding-top: 40px; border-top: 2px solid var(--glass-border);">
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
        
        <div class="shows-card reveal-on-scroll" style="margin-top: 20px;">
            <div class="card-header">
                <h3>Top Shows</h3>
                <a href="{{ route('admin.shows.index') }}" class="view-all">View All</a>
            </div>
            <ul class="shows-list">
                @php($topShows = \App\Models\Show::with('dj')->orderByDesc('listener_count')->take(3)->get())
                @if($topShows->count() > 0)
                    @foreach($topShows as $index => $show)
                        <li class="show-item" style="padding: 15px 0; border-bottom: 1px solid var(--glass-border); {{ !$loop->last ? '' : 'border-bottom: none;' }}">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <span style="color: var(--accent); font-weight: 700; font-size: 1.2rem; min-width: 30px;">#{{ $index + 1 }}</span>
                                <div class="show-avatar" style="background-image: url('{{ $show->hero_image ?? asset('assets/images/studio.jpg') }}')"></div>
                                <div class="show-details" style="flex: 1;">
                                    <div class="show-name">{{ $show->title }}</div>
                                    <div class="show-host">{{ $show->dj?->stage_name ?? $show->dj?->name ?? 'TBA' }}</div>
                                    <div class="show-stats" style="margin-top: 8px;">
                                        <div class="show-stat">
                                            <i class="fas fa-headphones"></i> {{ number_format($show->listener_count ?? 0) }} listeners
                                        </div>
                                        <div class="show-stat">
                                            <i class="fas fa-eye"></i> {{ number_format($show->view_count ?? 0) }} views
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li style="padding: 30px; text-align: center; color: var(--text-secondary);">
                        <i class="fas fa-broadcast-tower" style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;"></i>
                        <div>No shows data available yet</div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    
    <!-- Site Analytics Section -->
    <div class="analytics-section" style="margin-top: 40px; width: 100%;">
        <h2 class="section-title" style="font-family: 'Orbitron', sans-serif; color: var(--accent); margin-bottom: 30px; font-size: 1.8rem;">SITE ANALYTICS</h2>
        
        <div class="analytics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; width: 100%;">
            <div class="analytics-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <h3 style="color: var(--light); font-size: 1rem; font-weight: 600;">Total News Views</h3>
                    <i class="fas fa-eye" style="color: var(--accent); font-size: 1.5rem;"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--accent); margin-bottom: 10px;">{{ number_format($stats['totalNewsViews']) }}</div>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Across all news articles</p>
            </div>
            
            <div class="analytics-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <h3 style="color: var(--light); font-size: 1rem; font-weight: 600;">Total Show Views</h3>
                    <i class="fas fa-broadcast-tower" style="color: var(--accent); font-size: 1.5rem;"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--accent); margin-bottom: 10px;">{{ number_format($stats['totalShowViews']) }}</div>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Across all shows</p>
            </div>
            
            <div class="analytics-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <h3 style="color: var(--light); font-size: 1rem; font-weight: 600;">Registered Users</h3>
                    <i class="fas fa-users" style="color: var(--accent); font-size: 1.5rem;"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--accent); margin-bottom: 10px;">{{ number_format($stats['totalUsers']) }}</div>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Total user accounts</p>
            </div>
        </div>
        
        <div class="top-content-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; width: 100%;">
            <div class="top-content-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border);">
                <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin-bottom: 20px; font-size: 1.2rem;">Top News Articles</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @forelse($topNews as $index => $news)
                        <li style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-top: 1px solid rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.1); margin: 0 -5px; padding-left: 5px; padding-right: 5px; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.borderTopColor='var(--accent)'; this.style.borderBottomColor='var(--accent)'" onmouseout="this.style.background='transparent'; this.style.borderTopColor='rgba(255,255,255,0.1)'; this.style.borderBottomColor='rgba(255,255,255,0.1)'">
                            <div style="flex: 1; min-width: 0;">
                                <a href="{{ route('news.show', $news->slug) }}" style="color: var(--light); font-weight: 600; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-decoration: none; display: block;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--light)'">{{ $news->title }}</a>
                                <div style="color: var(--text-secondary); font-size: 0.85rem;">{{ number_format($news->view_count) }} views • {{ number_format($news->like_count ?? 0) }} likes</div>
                            </div>
                            <span style="color: var(--accent); font-weight: 700; margin-left: 15px; min-width: 35px; text-align: right;">#{{ $index + 1 }}</span>
                        </li>
                    @empty
                        <li style="text-align: center; padding: 20px; color: var(--text-secondary);">
                            <i class="fas fa-newspaper" style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;"></i>
                            <p>No news articles yet</p>
                        </li>
                    @endforelse
                </ul>
            </div>
            
            <div class="top-content-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border);">
                <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin-bottom: 20px; font-size: 1.2rem;">Top Shows</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @forelse($topShows as $index => $show)
                        <li style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-top: 1px solid rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.1); margin: 0 -5px; padding-left: 5px; padding-right: 5px; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.borderTopColor='var(--accent)'; this.style.borderBottomColor='var(--accent)'" onmouseout="this.style.background='transparent'; this.style.borderTopColor='rgba(255,255,255,0.1)'; this.style.borderBottomColor='rgba(255,255,255,0.1)'">
                            <div style="flex: 1; min-width: 0;">
                                <a href="{{ route('shows.show', $show->slug) }}" style="color: var(--light); font-weight: 600; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-decoration: none; display: block;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--light)'">{{ $show->title }}</a>
                                <div style="color: var(--text-secondary); font-size: 0.85rem;">{{ number_format($show->view_count ?? 0) }} views • {{ number_format($show->listener_count ?? 0) }} listeners</div>
                            </div>
                            <span style="color: var(--accent); font-weight: 700; margin-left: 15px; min-width: 35px; text-align: right;">#{{ $index + 1 }}</span>
                        </li>
                    @empty
                        <li style="text-align: center; padding: 20px; color: var(--text-secondary);">
                            <i class="fas fa-broadcast-tower" style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;"></i>
                            <p>No shows data available</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions" style="margin-top: 50px;">
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
        
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-dash.js') }}" defer></script>
@endpush
