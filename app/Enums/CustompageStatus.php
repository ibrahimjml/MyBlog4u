<?php

namespace App\Enums;

enum CustomPageStatus: int
{
  case ACTIVE = 1;
  case INACTIVE = 0;

  public function label(): string
  {
    return match($this) {
      self::ACTIVE => 'Active',
      self::INACTIVE => 'Inactive',
    };
  }
}