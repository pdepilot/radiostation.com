<x-filament-panels::page>
    <form id="site-settings-form" onsubmit="return false;">
        {{ $this->form }}

        <x-filament-actions::modals />

        <div class="mt-6 flex justify-end gap-3">
            {{-- Primary AJAX save button --}}
            <button type="button" id="ajax-save-btn" class="fi-btn fi-btn-color-primary fi-btn-size-md inline-flex items-center justify-center gap-1.5 rounded-lg border font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-color-primary fi-size-md bg-primary-600 text-white hover:bg-primary-500 focus-visible:ring-primary-600 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-500 px-3 py-2 text-sm">
                <span>Save</span>
            </button>
        </div>
    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('site-settings-form');
            const ajaxBtn = document.getElementById('ajax-save-btn');
            
            // Primary AJAX save function
            function saveViaAjax() {
                if (!form) return;
                
                // Collect form data from Livewire inputs
                const data = {};
                const inputs = form.querySelectorAll('input[type="text"], input[type="url"]');
                
                inputs.forEach(input => {
                    let key = null;
                    // Try different ways to get the field name
                    const wireModel = input.getAttribute('wire:model') || input.getAttribute('wire:model.defer');
                    if (wireModel) {
                        key = wireModel.replace('data.', '');
                    } else if (input.name) {
                        key = input.name.replace('data.', '');
                    } else if (input.id) {
                        // Try to extract from ID
                        const idMatch = input.id.match(/(facebook_url|twitter_url|instagram_url|youtube_url|tiktok_url)/);
                        if (idMatch) key = idMatch[1];
                    }
                    
                    if (key && (key.includes('facebook_url') || key.includes('twitter_url') || key.includes('instagram_url') || key.includes('youtube_url') || key.includes('tiktok_url'))) {
                        data[key] = input.value || null;
                    }
                });
                
                // If we didn't get data, try alternative method - get from Livewire component
                if (Object.keys(data).length === 0) {
                    try {
                        const livewireId = @js($this->getId());
                        if (window.Livewire && window.Livewire.find(livewireId)) {
                            const component = window.Livewire.find(livewireId);
                            const formData = component.get('data');
                            if (formData) {
                                Object.assign(data, formData);
                            }
                        }
                    } catch(e) {
                        console.log('Could not get Livewire data:', e);
                    }
                }
                
                // Ensure all fields are present (even if null)
                ['facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'tiktok_url'].forEach(field => {
                    if (!(field in data)) {
                        data[field] = null;
                    } else {
                        // Clean the value
                        data[field] = data[field] ? data[field].trim() : null;
                        data[field] = data[field] === '' ? null : data[field];
                    }
                });
                
                console.log('Saving via AJAX:', data);
                
                fetch('{{ route("admin.api.social-media-settings") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type') || '';
                    let responseText = await response.text();
                    
                    // Check if response is JSON
                    if (!contentType.includes('application/json')) {
                        console.error('Non-JSON response received:', responseText.substring(0, 500));
                        // Try to parse as JSON anyway (sometimes content-type is wrong)
                        try {
                            return JSON.parse(responseText);
                        } catch(e) {
                            throw new Error('Server returned HTML instead of JSON. Status: ' + response.status);
                        }
                    }
                    
                    // Parse JSON
                    try {
                        return JSON.parse(responseText);
                    } catch(e) {
                        console.error('JSON parse error:', e);
                        console.error('Response text:', responseText.substring(0, 500));
                        throw new Error('Invalid JSON response from server');
                    }
                })
                .then(result => {
                    if (result.success) {
                        (window.showSuccess || window.showNotification)('Settings saved successfully!', 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        (window.showError || window.showNotification)('Failed to save: ' + (result.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('AJAX save error:', error);
                    (window.showError || window.showNotification)('Failed to save settings: ' + (error.message || 'Please try again'), 'error');
                });
            }
            
            // Attach AJAX save to primary save button
            if (ajaxBtn) {
                ajaxBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    saveViaAjax();
                });
            }
            
            // Also handle Enter key in form inputs
            if (form) {
                form.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                        e.preventDefault();
                        saveViaAjax();
                    }
                });
            }
        });
    </script>
</x-filament-panels::page>

