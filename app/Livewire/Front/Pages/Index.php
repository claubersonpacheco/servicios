<?php

namespace App\Livewire\Front\Pages;

use Livewire\Attributes\Layout;

use Livewire\Component;

#[Layout("layouts.front.home")]
class Index extends Component
{
    public function render()
    {
        return view('livewire.front.pages.index');
    }
}
