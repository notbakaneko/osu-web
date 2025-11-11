<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Jobs\Notifications;

use App\Models\NewsPost;
use App\Models\Notification;
use App\Models\UserNotificationOption;

class NewsPostNew extends BroadcastNotificationBase
{
    const DELIVERY_MODE_DEFAULTS = ['mail' => false, 'push' => false];
    const NOTIFICATION_OPTION_NAME = Notification::NEWS_POST_NEW;

    public static function getMailLink(Notification $notification): string
    {
        return route('news.show', $notification->details['slug']);
    }

    public function __construct(protected NewsPost $newsPost)
    {
        parent::__construct();
    }

    public function getDetails(): array
    {
        return [
            'cover_url' => $this->newsPost->notificationCover(),
            'news_post_id' => $this->newsPost->getKey(),
            'slug' => $this->newsPost->slug,
            'title' => $this->newsPost->title(),
        ];
    }

    public function getListeningUserIds(): array
    {
        return UserNotificationOption::where('name', static::NOTIFICATION_OPTION_NAME)->pluck('user_id')->all();
    }

    public function getNotifiable()
    {
        return $this->newsPost;
    }
}
