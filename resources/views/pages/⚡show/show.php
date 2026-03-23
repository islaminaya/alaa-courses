<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::home')] class extends Component
{
    public Course $course;

    public $isEnrolled = false;

    public $showEnrollModal = false;

    public $userHasReviewed = false;

    public $relatedCourses;

    public $reviewLimit = 1;

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->relatedCourses = $this->getRelatedCourses();
        $this->course->load(['reviews'])->loadCount('reviews');

        if (Auth::check()) {
            $this->isEnrolled = $this->course->isEnrolledBy(Auth::user());
            $this->userHasReviewed = $this->course->hasReviewBy(Auth::user());
        }
    }

    public function hydrate()
    {
        $this->course->load(['reviews'])->loadCount('reviews');
    }

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
        dd('clicked');
        $this->reviewLimit += 3;
        $this->reviews();
    }

    public function checkEnrollmentStatus()
    {
        if (Auth::check()) {
            $this->isEnrolled = $this->course->isEnrolledBy(Auth::user());
        }
    }

    public function enrollCourse()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isEnrolled) {
            return;
        }

        // Create enrollment
        Auth::user()->enrollments()->create([
            'course_id' => $this->course->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $this->isEnrolled = true;
        $this->showEnrollModal = false;

        // Update course students count
        $this->course->increment('students_count');

        session()->flash('success', 'Successfully enrolled in '.$this->course->title);

        // Redirect to course learning page or dashboard
        // return redirect()->route('courses.learn', $this->course);
    }

    public function enrollNow()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isEnrolled) {
            session()->flash('message', 'You are already enrolled in this course.');

            return;
        }

        // For free courses, enroll immediately
        if ($this->course->price == 0) {
            $this->createEnrollment();
            $this->isEnrolled = true;
            session()->flash('success', 'Successfully enrolled in the course!');

            return redirect()->route('courses.learn', $this->course);
        }

        // For paid courses, redirect to checkout
        return redirect()->route('checkout', $this->course);
    }

    protected function createEnrollment()
    {
        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $this->course->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        // Increment students count
        $this->course->increment('students_count');
    }

    protected function getRelatedCourses()
    {
        return Course::where('category', $this->course->category)
            ->where('id', '!=', $this->course->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();
    }

    public function submitReview()
    {
        dd($this->reviewRating, $this->reviewComment);
    }

    public function toggleEnrollModal()
    {
        $this->showEnrollModal = ! $this->showEnrollModal;
    }
};
