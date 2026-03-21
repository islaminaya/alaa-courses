<div class="min-h-screen bg-gray-50 container mx-auto" x-data="{
    showFilters: false,
    scrolled: false
}" x-init="window.addEventListener('scroll', () => {
    scrolled = window.scrollY > 20
})">
    {{-- Mobile Header with Search --}}
    <div class="sticky top-0 z-40 bg-white transition-shadow duration-200"
        :class="scrolled ? 'shadow-md' : 'border-b border-gray-200'">

        {{-- Logo and Title --}}
        <div class="px-4 py-4 ">
            <div class="flex items-center justify-between mb-4 ">
                <a wire:navigate href="{{ route('home') }}" class="flex items-center gap-3 ">
                    <div
                        class="w-10 h-10 bg-linear-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <flux:icon.academic-cap class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ config('app.name') }}</h1>
                        <p class="text-xs text-gray-600">Your Medical Education</p>
                    </div>
                </a>

                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" wire:navigate
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:button type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer" data-test="logout-button">
                                {{ __('Log out') }}
                            </flux:button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
            {{-- Search Bar --}}
            <flux:input wire:model.live.debounce.300ms="searchQuery" type="search" icon="magnifying-glass"
                placeholder="Search courses..." />
        </div>

        {{-- Category Pills (Horizontal Scroll) --}}
        <div class="px-4 pb-3 overflow-x-auto scrollbar-hide">
            <div class="flex gap-2">
                <button wire:click="selectCategory('all')"
                    class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all duration-200 active:scale-95
                    {{ $selectedCategory === 'all'
                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30'
                        : 'bg-gray-100 text-gray-700 active:bg-gray-200' }}">All</button>
                @foreach ($categories as $key => $label)
                    <button wire:click="selectCategory('{{ $label->id }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all duration-200 active:scale-95
                               {{ $selectedCategory === (string) $label->id
                                   ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30'
                                   : 'bg-gray-100 text-gray-700 active:bg-gray-200' }}">
                        {{ ucfirst($label->name) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Stats Banner (Optional) --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-6">
        <div class="flex items-center justify-around text-center max-w-2xl mx-auto">
            <div>
                <div class="text-2xl font-bold">{{ $this->courseCount }}+</div>
                <div class="text-xs opacity-90">Courses</div>
            </div>
            <div class="w-px h-8 bg-white/30"></div>
            <div>
                <div class="text-2xl font-bold">{{ $this->studentCount }}+</div>
                <div class="text-xs opacity-90">Students</div>
            </div>
            <div class="w-px h-8 bg-white/30"></div>
            <div>
                <div class="text-2xl font-bold">{{ $this->averageRating }}★</div>
                <div class="text-xs opacity-90">Average Rating</div>
            </div>
        </div>
    </div>

    {{-- Course Cards Grid --}}
    <div class="px-4 py-6">
        @if ($this->courses->isEmpty())
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div
                    class="w-32 h-32 mb-6 bg-linear-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <flux:icon.academic-cap class="w-16 h-16 text-gray-400" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No courses found</h3>
                <p class="text-gray-600 text-sm mb-6 max-w-xs">
                    We couldn't find any courses matching your criteria. Try adjusting your filters.
                </p>
                <flux:button wire:click="$set('selectedCategory', 'all'); $set('searchQuery', '')" variant="primary">
                    View All Courses
                </flux:button>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($this->courses as $course)
                    <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 active:scale-98"
                        x-data="{ bookmarked: false }">

                        {{-- Course Image --}}
                        <div
                            class="relative aspect-[4/3] bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 overflow-hidden">
                            @if ($course->image)
                                <img src="{{ $course->image }}" alt="{{ $course->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                    loading="lazy">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <flux:icon.academic-cap class="w-20 h-20 text-white opacity-40" />
                                </div>
                            @endif

                            {{-- Category Badge --}}
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-3 py-1.5 bg-white/95 backdrop-blur-sm rounded-full text-xs font-bold text-gray-900 shadow-lg">
                                    {{ ucfirst($course->category->name) }}
                                </span>
                            </div>

                            {{-- Bookmark Button --}}
                            {{-- <button @click.prevent="bookmarked = !bookmarked"
                                class="absolute top-3 right-3 w-9 h-9 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg transition-transform active:scale-90">
                                <flux:icon.heart class="w-5 h-5 transition-colors"
                                    :class="bookmarked ? 'text-red-500 fill-current' : 'text-gray-600'" />
                            </button> --}}

                            {{-- Duration Badge --}}
                            @if ($course->duration)
                                <div class="absolute bottom-3 right-3">
                                    <span
                                        class="px-2.5 py-1 bg-black/70 backdrop-blur-sm rounded-lg text-xs font-medium text-white flex items-center gap-1.5">
                                        <flux:icon.clock class="w-3.5 h-3.5" />
                                        {{ $course->duration }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Course Info --}}
                        <a href="{{ route('courses.show', $course) }}" wire:navigate
                            class="block p-4 hover:bg-gray-50 transition-colors">
                            <h3
                                class="font-bold text-gray-900 text-base mb-2 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                                {{ $course->title }}
                            </h3>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                {{ $course->description }}
                            </p>

                            {{-- Course Meta --}}
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    @if ($course->students_count)
                                        <span class="flex items-center gap-1">
                                            <flux:icon.users class="w-4 h-4" />
                                            {{ number_format($course->students_count) }}
                                        </span>
                                    @endif

                                    @if ($course->rating)
                                        <span class="flex items-center gap-1">
                                            <flux:icon.star class="w-4 h-4 text-yellow-400 fill-current" />
                                            <span
                                                class="font-semibold text-gray-700">{{ number_format($course->rating, 1) }}</span>
                                        </span>
                                    @endif
                                </div>

                                @if ($course->is_new)
                                    <span
                                        class="px-2 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-md border border-green-200">
                                        NEW
                                    </span>
                                @endif
                            </div>

                            {{-- Price and CTA --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    @if ($course->price > 0)
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-2xl font-bold text-gray-900">
                                                ${{ number_format($course->price, 0) }}
                                            </span>
                                            @if ($course->original_price)
                                                <span class="text-sm text-gray-400 line-through">
                                                    ${{ number_format($course->original_price, 0) }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-2xl font-bold text-green-600">Free</span>
                                    @endif
                                </div>

                                <flux:button size="sm" variant="primary" icon:trailing="eye"
                                    class="group-hover:scale-105 transition-transform shadow-lg">
                                    Show
                                </flux:button>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <flux:button wire:click='loadMore' variant="primary" icon:trailing="arrow-down"
                    class="w-full sm:w-auto">
                    Load More Courses
                </flux:button>
            </div>
        @endif
    </div>

    {{-- Bottom Navigation (Mobile) --}}
    @auth
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 sm:hidden z-50">
            <div class="grid grid-cols-4 gap-1 px-2 py-2">
                <a href="#"
                    class="flex flex-col items-center justify-center py-2 px-3 rounded-lg bg-blue-50 text-blue-600">
                    <flux:icon.home class="w-6 h-6 mb-1" />
                    <span class="text-xs font-medium">Home</span>
                </a>
                <a href="#"
                    class="flex flex-col items-center justify-center py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                    <flux:icon.academic-cap class="w-6 h-6 mb-1" />
                    <span class="text-xs font-medium">My Courses</span>
                </a>
                <a href="#"
                    class="flex flex-col items-center justify-center py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                    <flux:icon.heart class="w-6 h-6 mb-1" />
                    <span class="text-xs font-medium">Saved</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                    class="flex flex-col items-center justify-center py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                    <flux:icon.user class="w-6 h-6 mb-1" />
                    <span class="text-xs font-medium">Profile</span>
                </a>
            </div>
        </nav>
    @endauth

    {{-- Bottom Padding for Mobile Nav --}}
    <div class="h-20 sm:hidden"></div>
</div>

{{-- Custom Styles --}}
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .active\:scale-98:active {
        transform: scale(0.98);
    }
</style>
