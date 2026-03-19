@props([
    'width' => 'w-0'
])

<div class="relative inline-block h-6 w-6">
    <!-- Base Empty Star -->
    <svg class="h-6 w-6 text-gray-300 fill-current" viewBox="0 0 24 24">
        <path
            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
    </svg>

    <!-- Half-Filled Overlay -->
    <div class="absolute top-0 left-0 overflow-hidden {{ $width }}">
        <svg class="h-6 w-6 text-yellow-400 fill-current" viewBox="0 0 24 24">
            <path
                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
        </svg>
    </div>
</div>
