@extends('layouts.admin', ['title' => 'Comments Management'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    @if(session('status'))
        <div style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border); margin-bottom: 30px;">
        <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;" class="comments-stats">
            <div style="flex: 1; min-width: 150px; padding: 15px; background: rgba(255,0,0,0.1); border-radius: 10px; border: 1px solid var(--glass-border);">
                <div style="font-size: 2rem; color: var(--accent); font-weight: 700;">{{ $comments->total() }}</div>
                <div style="color: var(--text-secondary);">Total Comments</div>
            </div>
            <div style="flex: 1; min-width: 150px; padding: 15px; background: rgba(0,204,102,0.1); border-radius: 10px; border: 1px solid var(--glass-border);">
                <div style="font-size: 2rem; color: var(--success); font-weight: 700;">{{ $comments->where('is_approved', true)->count() }}</div>
                <div style="color: var(--text-secondary);">Approved</div>
            </div>
            <div style="flex: 1; min-width: 150px; padding: 15px; background: rgba(255,165,0,0.1); border-radius: 10px; border: 1px solid var(--glass-border);">
                <div style="font-size: 2rem; color: #ffa500; font-weight: 700;">{{ $comments->where('is_approved', false)->count() }}</div>
                <div style="color: var(--text-secondary);">Pending</div>
            </div>
        </div>

        <div>
            @forelse($comments as $comment)
                <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; margin-bottom: 15px; border: 1px solid var(--glass-border);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <strong style="color: var(--accent);">{{ $comment->name ?? $comment->user->name ?? 'Guest' }}</strong>
                                @if($comment->user)
                                    <span style="background: var(--success); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">Verified</span>
                                @endif
                                @if($comment->is_approved)
                                    <span style="background: var(--success); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">Approved</span>
                                @else
                                    <span style="background: #ffa500; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">Pending</span>
                                @endif
                            </div>
                            <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 10px;">
                                <a href="{{ route('news.show', $comment->newsPost) }}" style="color: var(--accent); text-decoration: none;">
                                    <i class="fas fa-newspaper"></i> {{ $comment->newsPost->title }}
                                </a>
                            </div>
                            <p style="color: var(--light); line-height: 1.6; margin-bottom: 10px;">{{ $comment->body }}</p>
                            <div style="color: var(--text-secondary); font-size: 0.85rem;">
                                <i class="far fa-clock"></i> {{ $comment->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            @if(!$comment->is_approved)
                                <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: var(--success); color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem;">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Reject and delete this comment?')">
                                    @csrf
                                    <button type="submit" style="background: #ff4444; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem;">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this comment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: var(--accent); color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px; color: var(--text-secondary);">
                    <i class="fas fa-comments" style="font-size: 3rem; opacity: 0.3; margin-bottom: 20px;"></i>
                    <p>No comments yet.</p>
                </div>
            @endforelse
        </div>

        {{ $comments->links('pagination::custom') }}
    </div>
@endsection

