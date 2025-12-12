@extends('layouts.admin', ['title' => 'Edit Featured Sponsor'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <a href="{{ route('admin.adverts.index') }}" style="background: var(--glass); color: var(--light); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid var(--glass-border); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'; this.style.background='rgba(255,0,0,0.1)'" onmouseout="this.style.borderColor='var(--glass-border)'; this.style.background='var(--glass)'">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border); max-width: 800px; margin: 0 auto;">
        <form method="POST" action="{{ route('admin.adverts.update', $advert) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Title</label>
                    <input type="text" name="title" value="{{ old('title', $advert->title) }}" required style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    @error('title')
                        <span style="color: var(--accent); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Type</label>
                    <select name="type" id="advertType" required style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                        <option value="image" @selected(old('type', $advert->type) === 'image')>Image</option>
                        <option value="banner" @selected(old('type', $advert->type) === 'banner')>Banner</option>
                        <option value="popup" @selected(old('type', $advert->type) === 'popup')>Popup</option>
                        <option value="google_adsense" @selected(old('type', $advert->type) === 'google_adsense')>Google AdSense</option>
                    </select>
                </div>

                <div id="imageFields" style="display: none;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Image URL</label>
                        <input type="url" name="image_url" value="{{ old('image_url', $advert->image_url) }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Link URL</label>
                        <input type="url" name="link_url" value="{{ old('link_url', $advert->link_url) }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    </div>
                </div>

                <div id="adsenseFields" style="display: none;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Google AdSense Code</label>
                        <textarea name="google_adsense_code" rows="5" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); resize: vertical;">{{ old('google_adsense_code', $advert->google_adsense_code) }}</textarea>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Position</label>
                    <select name="position" required style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                        <option value="sidebar" @selected(old('position', $advert->position) === 'sidebar')>Sidebar</option>
                        <option value="header" @selected(old('position', $advert->position) === 'header')>Header</option>
                        <option value="footer" @selected(old('position', $advert->position) === 'footer')>Footer</option>
                        <option value="content" @selected(old('position', $advert->position) === 'content')>Content</option>
                        <option value="popup" @selected(old('position', $advert->position) === 'popup')>Popup</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Description (Optional)</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); resize: vertical;">{{ old('description', $advert->description) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: flex; align-items: center; gap: 10px; color: var(--light);">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $advert->is_active)) style="width: auto;">
                            <span>Active</span>
                        </label>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 10px; color: var(--light);">
                            <input type="checkbox" name="show_to_registered" value="1" @checked(old('show_to_registered', $advert->show_to_registered)) style="width: auto;">
                            <span>Show to Registered Users</span>
                        </label>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Start Date (Optional)</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $advert->starts_at?->format('Y-m-d\TH:i')) }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">End Date (Optional)</label>
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $advert->ends_at?->format('Y-m-d\TH:i')) }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    </div>
                </div>

                <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; margin-top: 10px;">
                    <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 10px;">Statistics:</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <div style="color: var(--accent); font-weight: 600; font-size: 1.2rem;">{{ number_format($advert->view_count) }}</div>
                            <div style="color: var(--text-secondary); font-size: 0.85rem;">Views</div>
                        </div>
                        <div>
                            <div style="color: var(--accent); font-weight: 600; font-size: 1.2rem;">{{ number_format($advert->click_count) }}</div>
                            <div style="color: var(--text-secondary); font-size: 0.85rem;">Clicks</div>
                        </div>
                    </div>
                    @if($advert->click_count > 0)
                        <div style="margin-top: 10px; color: var(--text-secondary); font-size: 0.9rem;">
                            CTR: {{ number_format(($advert->click_count / max($advert->view_count, 1)) * 100, 2) }}%
                        </div>
                    @endif
                </div>

                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <button type="submit" style="background: var(--success); color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-save"></i> Update Sponsor
                    </button>
                    <a href="{{ route('admin.adverts.index') }}" style="background: transparent; color: var(--light); padding: 12px 30px; border: 1px solid var(--glass-border); border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center;">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('advertType').addEventListener('change', function() {
            const type = this.value;
            const imageFields = document.getElementById('imageFields');
            const adsenseFields = document.getElementById('adsenseFields');
            
            imageFields.style.display = (type === 'image' || type === 'banner' || type === 'popup') ? 'block' : 'none';
            adsenseFields.style.display = (type === 'google_adsense') ? 'block' : 'none';
        });
        
        // Trigger on load
        document.getElementById('advertType').dispatchEvent(new Event('change'));
    </script>
@endsection

