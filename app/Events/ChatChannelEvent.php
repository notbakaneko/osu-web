<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Events;

use App\Models\Chat\Channel as ChatChannel;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ChatChannelEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $broadcastQueue;
    public $action;
    public $channelId;
    public $userId;

    public function __construct(ChatChannel $channel, User $user, string $action)
    {
        // TODO: different queue? conditional queue?
        $this->broadcastQueue = config('osu.notification.queue_name');

        $this->action = $action;
        $this->channelId = $channel->getKey();
        $this->userId = $user->getKey();
    }

    public function broadcastAs()
    {
        return "chat.channel.{$this->action}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("private:user:{$this->userId}");
    }

    public function broadcastWith()
    {
        return ['channel_id' => $this->channelId];
    }
}
