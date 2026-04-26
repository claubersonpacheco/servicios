<?php

namespace App\Livewire\Front\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout("layouts.front.home")]
class Cookie extends Component
{
    public function render()
    {
        return view('livewire.front.pages.cookie');
    }
}
