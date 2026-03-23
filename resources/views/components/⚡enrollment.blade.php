<?php

use App\Models\Course;
use Livewire\Component;

new class extends Component {
    public Course $course;

    public $couponCode = '';

    public $response;

    public $price;

    public $original_price;

    public function mount()
    {
        $this->price = $this->course->price;
        $this->original_price = $this->course->original_price;
    }

    public function check()
    {
        $coupon = $this->course->getActiveCoupon();

        if ($coupon === null || $coupon->code !== $this->couponCode) {
            $this->response = 'The coupon is not available';
            return;
        }

        $this->original_price = ($this->original_price * (100 - $coupon->discount)) / 100;
        $this->price = ($this->price * (100 - $coupon->discount)) / 100;
    }
};
?>

<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
    {{-- Price Section --}}
    <section>
        <div class="p-6 bg-linear-to-br from-blue-600 via-blue-700 to-teal-600 text-white">
            @if ($this->price > 0)
                <div class="mb-2">
                    <div class="flex items-baseline gap-3">
                        <span class="font-serif text-5xl font-normal">${{ number_format($this->price, 2) }}</span>
                        @if ($this->original_price)
                            <span
                                class="text-2xl text-blue-200 line-through">${{ number_format($this->original_price, 2) }}</span>
                        @endif
                    </div>
                    @if ($this->original_price)
                        <p class="text-sm text-blue-100 font-medium mt-2">
                            Save
                            {{ round((($this->original_price - $this->price) / $this->original_price) * 100) }}%
                            • Limited time offer
                        </p>
                    @endif
                </div>
            @else
                <div class="font-serif text-5xl font-normal mb-2">Free</div>
            @endif
        </div>

        @if ($this->price > 0)
            <div class="p-6">
                <form wire:submit.prevent='check'>
                    <flux:label>Have a coupon code</flux:label>
                    <div class="flex gap-3">
                        <flux:input wire:model='couponCode' />
                        <flux:error name="coupon" />
                        <flux:button class="flex self-end" wire:click='check'>Check</flux:button>
                    </div>
                    @if ($response)
                        <span class="text-red-500 text-sm">{{ $response }}</span>
                    @endif
                </form>
            </div>
        @endif

        <div class="p-6">
            <form x-data="{ loading: false }" x-on:submit="loading = true"
                action="{{ route('checkout', ['course' => $course, 'code' => $this->couponCode]) }}" method="post">
                @csrf
                <button x-bind:disabled="loading" type="submit"
                    class="w-full bg-linear-to-r from-blue-600 to-teal-600 hover:from-blue-700 hover:to-teal-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5 group disabled:opacity-50 disabled:cursor-not-allowed">
                    <div x-show="!loading" class="flex items-center justify-center gap-2">
                        Enroll Now
                        <flux:icon.arrow-right />
                    </div>
                    <div x-show="loading" class="flex justify-center">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </button>
            </form>

            {{-- Course Includes --}}
            {{-- <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                <p class="font-semibold text-gray-900 mb-4">This course includes:</p>
                @foreach ([['icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'text' => '12 hours on-demand video'], ['icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'text' => 'Downloadable resources'], ['icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'text' => 'Access on mobile and desktop'], ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'text' => 'Certificate of completion']] as $item)
                    <div class="flex items-center gap-3 text-sm text-gray-700">
                        <svg class="w-5 h-5 text-teal-600 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="{{ $item['icon'] }}" />
                        </svg>
                        <span>{{ $item['text'] }}</span>
                    </div>
                @endforeach
            </div> --}}
        </div>
    </section>
</div>
