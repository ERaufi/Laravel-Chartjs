<?php

namespace App\View\Components;

use App\Models\Countries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class filters extends Component
{
    /**
     * Create a new component instance.
     */
    public $showContries;
    public function __construct($showContries)
    {
        //
        $this->showContries = $showContries;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $countries = Countries::orderBy('name')->get();
        return view('components.filters', compact('countries'));
    }
}
