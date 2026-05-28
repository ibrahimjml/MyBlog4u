<?php

namespace App\View\Components;

use App\Enums\FollowerStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FollowButton extends Component
{
    public ?FollowerStatus $status;
    public int $userId;
    public string $type;

    public function __construct(?int $status, int $userId, string $type = 'icon')
    {
         $this->status = $status !== null
            ? FollowerStatus::tryFrom($status)
            : null;

        $this->userId = $userId;
        $this->type = $type;
    }
      public function stateClass(array $state): string
    {
        return match ($this->status) {
            FollowerStatus::ACCEPTED => $state['accepted'] ?? '',
            FollowerStatus::PENDING  => $state['pending'] ?? '',
            default                  => $state['default'] ?? '',
        };
    }
    // for blog aside page
   public function icon(): string
    {
        return match ($this->status) {
            FollowerStatus::ACCEPTED => 'check',
            FollowerStatus::PENDING  => 'hourglass-half',
            default                  => 'plus',
        };
    }
    // for profile page
    public function label(): string
    {
        return match ($this->status) {
            FollowerStatus::ACCEPTED => 'Following',
            FollowerStatus::PENDING  => 'Requested',
            default                  => 'Follow',
        };
    }
    // for followers page
    public function followersButtons(): string
    {
        return match ($this->status) {
            FollowerStatus::ACCEPTED => 'UnFollow',
            FollowerStatus::PENDING  => 'Requested',
            default                  => 'Follow back',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.follow-button');
    }
}
