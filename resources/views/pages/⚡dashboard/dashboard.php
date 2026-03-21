<?php

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component
{
    public $selectedCategory = 'all';

    public $categories;

    public $limit = 4;

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function loadMore()
    {
        $this->limit += 4;
    }

    #[Computed()]
    public function courses()
    {
        return Auth::user()->courses;
    }
};
