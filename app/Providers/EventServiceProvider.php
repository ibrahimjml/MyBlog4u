<?php

namespace App\Providers;

use App\Events\AuditLogCreatedEvent;
use App\Events\CommentCreatedEvent;
use App\Events\FollowUserEvent;
use App\Events\MentionedUserEvent;
use App\Events\NewRegistered;
use App\Events\PostCreatedEvent;
use App\Events\PostLikedEvent;
use App\Events\PostReportEvent;
use App\Events\ProfileViewedEvent;
use App\Events\ReplyCommentEvent;
use App\Events\ReportStatusUpdateEvent;
use App\Events\SitemapRendering;
use App\Listeners\BuildSitemap;
use App\Listeners\LogAuditActivity;
use App\Listeners\NotifyAdminNewUser;
use App\Listeners\SendCommentNotification;
use App\Listeners\SendFollowNotification;
use App\Listeners\SendLikeNotification;
use App\Listeners\SendMentionedNotification;
use App\Listeners\SendPostNotification;
use App\Listeners\SendPostReportNotification;
use App\Listeners\SendProfileViewNotification;
use App\Listeners\SendReplyNotification;
use App\Listeners\SendReportStatusNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewRegistered::class => [
          NotifyAdminNewUser::class,
        ],
        PostCreatedEvent::class => [
          SendPostNotification::class,
        ],
        CommentCreatedEvent::class => [
          SendCommentNotification::class,
        ],
        ReplyCommentEvent::class => [
          SendReplyNotification::class,
        ],
        PostLikedEvent::class => [
          SendLikeNotification::class,
        ],
        FollowUserEvent::class => [
          SendFollowNotification::class,
        ],
        ProfileViewedEvent::class => [
          SendProfileViewNotification::class,
        ],
        PostReportEvent::class => [
          SendPostReportNotification::class,
        ],
        ReportStatusUpdateEvent::class => [
          SendReportStatusNotification::class,
        ],
        MentionedUserEvent::class => [
          SendMentionedNotification::class,
        ],
        SitemapRendering::class => [
          BuildSitemap::class,
        ],
        AuditLogCreatedEvent::class => [
          LogAuditActivity::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
