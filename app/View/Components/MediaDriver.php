<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use App\Enums\MediaDriver as Driver;
use Illuminate\View\Component;

class MediaDriver extends Component
{
    public string|null $selected;

    public array $options;

    public array $fields;
    public function __construct($selected = null)
    {
        $this->selected = $selected;

        $this->options = Driver::cases();

        $this->fields = collect(Driver::cases())
            ->mapWithKeys(fn ($driver) => [
                $driver->value => $driver->fields()
            ])
            ->toArray();
    }

    
    public function render(): View|Closure|string
    {
        return view('components.media-driver');
    }
}
