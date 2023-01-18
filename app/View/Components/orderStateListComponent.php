<?php

namespace App\View\Components;

use Illuminate\View\Component;

class orderStateListComponent extends Component
{
    public $processes;
    public function __construct($processes)
    {
        $this->processes = $processes;
    }
    
    public function render()
    {
        return view(theme('components.order-state-list-component'));
    }
}
