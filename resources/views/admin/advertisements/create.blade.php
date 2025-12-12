@extends('layouts.admin', ['title' => 'Add Featured Sponsor'])

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
        <form method="POST" action="{{ route('admin.adverts.store') }}">
            @csrf

            <div style="display: grid; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    @error('title')
                        <span style="color: var(--accent); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Type</label>
                    <select name="type" id="advertType" required style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                        <option value="image" @selected(old('type') === 'image')>Image</option>
                        <option value="banner" @selected(old('type') === 'banner')>Banner</option>
                        <option value="popup" @selected(old('type') === 'popup')>Popup</option>
                        <option value="google_adsense" @selected(old('type') === 'google_adsense')>Google AdSense</option>
                    </select>
                    @error('type')
                        <span style="color: var(--accent); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div id="imageFields" style="display: none;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Image</label>
                        <div style="display: grid; grid-template-columns: 1fr auto; gap: 10px; align-items: end;">
                            <input type="url" name="image_url" id="imageUrlInput" value="{{ old('image_url') }}" placeholder="Enter image URL or upload file" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                            <input type="file" name="image_file" id="imageFileInput" accept="image/*" style="display: none;" onchange="handleImageUpload(this)">
                            <button type="button" onclick="document.getElementById('imageFileInput').click();" style="background: var(--accent); color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; white-space: nowrap;">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </div>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 5px;">Enter a URL or upload an image file (JPG, PNG, GIF - Max 5MB)</p>
                        <div id="imagePreview" style="margin-top: 15px; display: none;">
                            <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid var(--glass-border);">
                        </div>
                        @error('image_url')
                            <span style="color: var(--accent); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Link URL</label>
                        <input type="url" name="link_url" value="{{ old('link_url') }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                        @error('link_url')
                            <span style="color: var(--accent); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div id="adsenseFields" style="display: none;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Google AdSense Code</label>
                        <textarea name="google_adsense_code" rows="5" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); resize: vertical;">{{ old('google_adsense_code') }}</textarea>
                        @error('google_adsense_code')
                            <span style="color: var(--accent); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 5px;">Paste your AdSense ad code here</p>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Position</label>
                    <select name="position" required style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                        <option value="sidebar" @selected(old('position') === 'sidebar')>Sidebar</option>
                        <option value="header" @selected(old('position') === 'header')>Header</option>
                        <option value="footer" @selected(old('position') === 'footer')>Footer</option>
                        <option value="content" @selected(old('position') === 'content')>Content</option>
                        <option value="popup" @selected(old('position') === 'popup')>Popup</option>
                    </select>
                    @error('position')
                        <span style="color: var(--accent); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Description (Optional)</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); resize: vertical;">{{ old('description') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: flex; align-items: center; gap: 10px; color: var(--light);">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) style="width: auto;">
                            <span>Active</span>
                        </label>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 10px; color: var(--light);">
                            <input type="checkbox" name="show_to_registered" value="1" @checked(old('show_to_registered')) style="width: auto;">
                            <span>Show to Registered Users</span>
                        </label>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Start Date (Optional)</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">End Date (Optional)</label>
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                    </div>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <button type="submit" style="background: var(--accent); color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-save"></i> Add Sponsor
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

