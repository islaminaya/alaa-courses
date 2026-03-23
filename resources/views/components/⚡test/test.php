<?php

use Livewire\Component;

new class extends Component
{
    public $title = '';

    public function save()
    {
        dd($this->title);
    }
};
