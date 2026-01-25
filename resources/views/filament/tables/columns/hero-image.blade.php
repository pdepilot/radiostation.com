@php
    use Illuminate\Support\Facades\Storage;
    
    $record = $getRecord();
    $imagePath = $record->hero_image;
    $fallbackUrl = asset('assets/images/studio.jpg');
    
    if ($imagePath && Storage::disk('public')->exists($imagePath)) {
        $imageUrl = Storage::disk('public')->url($imagePath);
    } else {
        $imageUrl = $fallbackUrl;
    }
@endphp

<img 
    src="{{ $imageUrl }}" 
    alt="Hero Image" 
    class="rounded-lg object-cover"
    style="width: 50px; height: 50px;"
    onerror="this.onerror=null; this.src='{{ $fallbackUrl }}'"
>

