<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PaginationComponent extends Component
{
    public $items;
    public $type = '';
    public function __construct($items, $type)
    {
        $this->items = $items;
        $this->type = $type;
    }

    
    public function render()
    {
        return view(theme('components.pagination-component'));
    }
}
