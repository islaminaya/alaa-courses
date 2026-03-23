<?php

use App\Events\ReviewSubmitted;
use App\Models\Course;
use App\Models\Review;
use Livewire\Component;

new class extends Component {
    public $rating;

    public $comment;

    public Course $course;

    public function mount()
    {
        $review = Review::query()
            ->where('course_id', $this->course->id)
            ->where('user_id', Auth::id())
            ->first();

        $this->rating = $review?->rating;

        $this->comment = $review?->comment;
    }

    public function submitReview()
    {
        Review::updateOrCreate(
            [
                'course_id' => $this->course->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $this->rating,
                'comment' => $this->comment,
            ],
        );

        ReviewSubmitted::dispatch($this->course);

        session()->flash('success', 'Review saved successfully');
    }
};
?>

<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" x-data="{
    rating: $wire.rating,
    comment: $wire.comment,
    hoveredRating: 0,
}">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-teal-600 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-white">Rate This Course</h3>
                <p class="text-xs text-blue-100">Share your experience</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="p-6">
        <form wire:submit.prevent="submitReview" class="space-y-5">
            {{-- Star Rating --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-3">Your
                    Rating</label>
                <div class="flex items-center justify-center gap-1 bg-gray-50 p-4 rounded-xl">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button"
                            @click="rating = {{ $i }}; $wire.set('rating', {{ $i }})"
                            @mouseenter="hoveredRating = {{ $i }}" @mouseleave="hoveredRating = 0"
                            class="focus:outline-none transition-transform hover:scale-110 active:scale-95">
                            <svg class="w-8 h-8 sm:w-9 sm:h-9 transition-colors cursor-pointer"
                                :class="(hoveredRating >= {{ $i }} || (hoveredRating ===
                                    0 && rating >= {{ $i }})) ?
                                'text-yellow-400 fill-current' : 'text-gray-300'"
                                viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    @endfor
                </div>
                <p class="text-center text-sm font-medium text-gray-700 mt-2" x-show="rating > 0">
                    <span x-text="rating"></span> out of 5 stars
                </p>
                @error('reviewRating')
                    <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                @enderror
            </div>

            {{-- Review Comment --}}
            <div>
                <label for="comment" class="block text-sm font-semibold text-gray-900 mb-3">Your Review</label>
                <textarea id="comment" wire:model.live="comment" rows="4"
                    class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                    placeholder="What did you like about this course?"></textarea>
                @error('comment')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-teal-600 hover:from-blue-700 hover:to-teal-700 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                :disabled="rating == 0">
                Submit Review
            </button>
        </form>
    </div>
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 1500)" x-transition
            class="absolute top-24 left-4 right-4 mx-auto max-w-md z-50 bg-linear-to-r from-teal-500 to-teal-600 text-white px-6 py-4 rounded-xl shadow-2xl">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="flex-1 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>
