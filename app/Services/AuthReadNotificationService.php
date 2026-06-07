<?php

namespace App\Services;

use App\Enums\NotificationType;
use Illuminate\Support\Facades\Log;

class AuthReadNotificationService
{
    public function readNotications(string $id)
    {
      $notifications = auth()->user()->notifications()->findOrFail($id);
      $notifications->markAsRead();

      $data = $notifications->data;
      $type = $data['type'] ?? null;
      $username = null;

      foreach ($data as $key => $value) {
          if (!$username && str_contains($key, 'username')) {
              $username = $value;
              break;
          }
      }
    if (in_array($type, array_map(fn($e) => $e->value, NotificationType::postRelated()), true)) {
         return to_route('single.post', $data['post_link']);
    } elseif (in_array($type, array_map(fn($e) => $e->value, NotificationType::userRelated()), true)) {
          return to_route('profile.home', $username);
      }
    }
}
