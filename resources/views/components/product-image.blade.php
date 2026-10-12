@props(['product', 'imageClass' => 'h-52 w-full object-cover'])

@php
    $fallbackImage = match ($product->category?->slug) {
        'serum' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=900&q=85',
        'cleanser' => 'https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?auto=format&fit=crop&w=900&q=85',
        'toner' => 'https://images.unsplash.com/photo-1707539160277-e39464517645?auto=format&fit=crop&w=900&q=85',
        'moisturizer' => 'https://images.unsplash.com/photo-1585652757141-8837d676fac8?auto=format&fit=crop&w=900&q=85',
        default => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=900&q=85',
    };
@endphp

<img src="{{ $product->image_path ? asset('storage/'.$product->image_path) : $fallbackImage }}" alt="Foto {{ $product->name }}" class="{{ $imageClass }}" loading="lazy">