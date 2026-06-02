<?php

namespace App\View\Components;

use App\Enums\Adplacements\AdPosition;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdPlacement extends Component
{
    public AdPosition $position;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public iterable $ads,
        AdPosition|string $position
    ){
        $this->position = $position instanceof AdPosition
            ? $position
            : AdPosition::from($position);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ad-placement');
    }
}
