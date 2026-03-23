<?php

use App\Models\Course;
use App\Models\Review;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {

    public Course $course;

    public $reviewLimit = 3;

    #[Computed()]
    public function reviews()
    {
        return Review::where('course_id', $this->course->id)
            ->orderBy('rating', 'desc')
            ->limit($this->reviewLimit)
            ->get();
    }

    public function loadMoreReviews()
    {
        $this->reviewLimit += 3;
    }
};
?>

<div>
    <div
        class="flex flex-col sm:flex-row items-start gap-8 p-8 bg-linear-to-br from-blue-50 via-teal-50 to-blue-50 rounded-2xl border border-blue-100">
        <div class="text-center sm:border-r sm:border-blue-200 sm:pr-8">
            <div class="font-serif text-6xl font-normal text-gray-900 mb-3">
                {{ number_format($course->rating, 1) }}</div>
            <div class="flex items-center justify-center gap-1 mb-2">
                @for ($r = 1; $r <= 5; $r++)
                    <x-star :width="$course->rating >= $r ? 'w-full' : ($course->rating >= $r - 0.5 ? 'w-1/2' : 'w-0')" />
                @endfor
            </div>
            <p class="text-sm text-gray-600 font-medium">Course rating</p>
        </div>

        <div class="flex-1 space-y-3 w-full">
            @foreach ($course->ratingBreakdown() as $star => $percentage)
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 w-12">{{ $star }}
                        star</span>

                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-400" style="width: {{ $percentage }}%">
                        </div>
                    </div>

                    <span class="text-sm text-gray-600 w-8">{{ $percentage }}%</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Reviews List --}}
    <div class="space-y-6 mt-6">
        @foreach ($this->reviews as $review)
            <div class="border-b border-gray-200 pb-6 last:border-0">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <span class="text-white font-bold">{{ $review->user->initials() }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $review->user->name }}
                        </p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex items-center">
                                @for ($j = 1; $j <= $review->rating; $j++)
                                    <flux:icon.star class="w-4 h-4 text-yellow-400 fill-current" />
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    {{ $review->comment }}
                </p>
            </div>
        @endforeach
        <div class="text-center">
            <flux:button type="button" wire:click='loadMoreReviews' icon:trailing="arrow-down">
                Load More Reviews
            </flux:button>
        </div>
    </div>
</div>
