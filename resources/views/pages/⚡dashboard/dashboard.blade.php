<div class="min-h-screen container mx-auto" x-data="{
    showFilters: false,
    scrolled: false
}" x-init="window.addEventListener('scroll', () => {
    scrolled = window.scrollY > 20
})">
    <div class="flex justify-between items-center">
        <div class="font-sans font-bold text-2xl flex items-center gap-2 text-blue-600">
            <flux:icon.academic-cap />
            My Courses
        </div>
        <a href="{{ route('courses.index') }}" wire:navigate class="group flex items-center justify-between gap-2 px-3 py-2 rounded-md text-blue-600 hover:bg-blue-600 hover:text-white transition duration-300">
            <flux:icon.home variant="mini" class="text-blue-600 group-hover:text-white transition duration-300" />
            All Courses
        </a>
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
                <a href="{{ route('courses.index') }}" wire:navigate>
                    <flux:button variant="primary">
                        View All Courses
                    </flux:button>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($this->courses as $course)
                    <livewire:course-card :key="$course->id" :course="$course"/>
                @endforeach
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
