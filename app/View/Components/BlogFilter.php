<?php

namespace App\View\Components;

use App\DTOs\BlogFilterDTO;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BlogFilter extends Component
{
    public function __construct(public BlogFilterDTO $filter)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.blog-filter');
    }
}
