@props([
    'max' => '10 MB',
    'formats' => 'JPG, JPEG, PNG, GIF, SVG, WebP',
    'webp' => true,
    'extra' => null,
])

<p class="mt-2 text-xs text-[#6a6a6a] leading-relaxed">
    Format: {{ $formats }}. Maksimal {{ $max }} per file.
    @if ($webp)
        JPG/PNG/GIF/WebP akan otomatis disimpan sebagai WebP.
    @endif
    @if ($extra)
        {{ $extra }}
    @endif
</p>
