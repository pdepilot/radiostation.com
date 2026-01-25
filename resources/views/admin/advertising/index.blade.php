{{-- resources/views/admin/advertising/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-orbitron text-4xl md:text-5xl font-black bg-gradient-to-r from-var(--accent) to-var(--highlight) bg-clip-text text-transparent drop-shadow-lg tracking-wider">
            Advertising Packages
        </h2>
    </x-slot>

    <div class="py-12 relative">
        <div class="cyber-grid"></div>
        
        <div class="container max-w-7xl mx-auto px-6 space-y-12">

            <!-- Success Message -->
            @if(session('status'))
                <div class="glass-card border border-live-green/50 backdrop-blur-xl p-6 rounded-2xl shadow-2xl animate-pulse">
                    <div class="flex items-center gap-4 text-live-green font-bold text-lg">
                        <span class="animate-ping">●</span>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Create New Package -->
            <div class="glass-card p-8 md:p-10 rounded-2xl border border-glass-border shadow-2xl hover:shadow-neon transition-all duration-500">
                <h3 class="font-orbitron text-3xl font-bold text-accent mb-8 flex items-center gap-4">
                    <span class="text-live-green animate-pulse text-4xl">●</span>
                    Create New Package
                </h3>

                <form method="POST" action="{{ route('admin.advertising.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @csrf

                    <x-neon-input name="name" label="Package Name" placeholder="e.g. Prime Time Dominator" :value="old('name')" required />
                    <x-neon-input name="duration_weeks" type="number" label="Duration (Weeks)" :value="old('duration_weeks', 4)" required />
                    <x-neon-input name="reach" label="Projected Reach" placeholder="e.g. 1.2M+ Listeners" :value="old('reach')" />
                    <x-neon-input name="price" type="number" label="Price (₦)" :value="old('price')" required />
                    <x-neon-input name="cta" label="CTA Button Text" placeholder="e.g. Dominate Now" :value="old('cta', 'Book Now')" />

                    <div class="lg:col-span-3">
                        <x-neon-textarea name="description" label="Description" rows="4" placeholder="Describe what makes this package legendary...">
                            {{ old('description') }}
                        </x-neon-textarea>
                    </div>

                    <div class="flex items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-accent mb-2">Status</label>
                            <select name="status" class="w-full bg-glass border border-glass-border rounded-xl px-5 py-4 text-light focus:border-accent focus:ring-2 focus:ring-accent/30 transition-all">
                                <option value="active" @selected(old('status', 'active') === 'active')>Active (Visible)</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive (Hidden)</option>
                            </select>
                        </div>

                        <button type="submit" class="px-8 py-4 bg-gradient-to-r from-accent to-accent-glow text-dark font-bold rounded-xl hover:scale-105 transform transition-all duration-300 shadow-lg hover:shadow-neon flex items-center gap-3">
                            <span>Create Package</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Packages -->
            <div class="space-y-8">
                @forelse($packages as $package)
                    <div class="glass-card rounded-2xl border border-glass-border shadow-2xl hover:shadow-neon transition-all duration-500 overflow-hidden">
                        <div class="p-8 space-y-6">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <h4 class="font-orbitron text-2xl font-bold text-highlight">{{ $package->name }}</h4>
                                    <div class="flex items-center gap-4 mt-2 text-sm">
                                        <span class="text-accent-glow font-bold">{{ $package->formatted_price }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $package->duration_label }}</span>
                                        @if($package->reach)
                                            <span class="text-gray-400">•</span>
                                            <span class="text-live-green font-medium">{{ $package->reach }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Status</div>
                                        {!! $package->status_badge !!}
                                    </div>
                                    <form method="POST" action="{{ route('admin.advertising.destroy', $package) }}" onsubmit="return confirm('⚠️ Permanently delete {{ addslashes($package->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-bold transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($package->description)
                                <p class="text-gray-300 leading-relaxed">{{ $package->description }}</p>
                            @endif

                            <!-- Update Form -->
                            <form method="POST" action="{{ route('admin.advertising.update', $package) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8 pt-8 border-t border-glass-border">
                                @csrf @method('PUT')

                                <x-neon-input name="name" :value="old('name', $package->name)" label="Name" />
                                <x-neon-input name="duration_weeks" type="number" :value="old('duration_weeks', $package->duration_weeks)" label="Weeks" />
                                <x-neon-input name="price" type="number" :value="old('price', $package->price)" label="Price (₦)" />
                                
                                <div>
                                    <label class="block text-sm font-bold text-accent mb-2">Status</label>
                                    <select name="status" class="w-full bg-glass border border-glass-border rounded-xl px-5 py-4 text-light focus:border-accent focus:ring-2 focus:ring-accent/30 transition-all">
                                        <option value="active" @selected(old('status', $package->status) === 'active')>Active</option>
                                        <option value="inactive" @selected(old('status', $package->status) === 'inactive')>Inactive</option>
                                    </select>
                                </div>

                                <div class="lg:col-span-4">
                                    <x-neon-textarea name="description" rows="3">
                                        {{ old('description', $package->description) }}
                                    </x-neon-textarea>
                                </div>

                                <div class="lg:col-span-4 flex justify-end">
                                    <button type="submit" class="px-8 py-4 bg-gradient-to-r from-live-green to-green-400 text-black font-bold rounded-xl hover:scale-105 transform transition-all duration-300 shadow-lg hover:shadow-live flex items-center gap-3">
                                        <span>Update Package</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="glass-card text-center p-16 rounded-2xl border border-glass-border">
                        <div class="text-6xl mb-6 opacity-20">No packages yet</div>
                        <p class="text-xl text-gray-400">Create your first advertising package above to start selling airtime like a cyber-baron.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>