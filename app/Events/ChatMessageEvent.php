<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Events;

use App\Models\Chat\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ChatMessageEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $broadcastQueue;
    public $message;
    public $userId;

    public function __construct(Message $message, $userId)
    {
        // TODO: different queue? conditional queue?
        $this->broadcastQueue = config('osu.notification.queue_name');

        // TODO: avoid serializing so handle doesn't need to perform queries to deserialize.
        $this->message = $message;
        $this->userId = $userId;
    }

    public function broadcastAs()
    {
        return 'chat.message.new';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        if ($this->message->channel->isPublic()) {
            return new Channel("chat:channel:{$this->message->channel->getKey()}");
        } else {
            // filter out sender.
            return $this->message->channel->users()->pluck('user_id')->filter(function ($userId) {
                return $userId !== $this->userId;
            })->map(function ($userId) {
                return new Channel("private:user:{$userId}");
            })->all();
        }
    }

    public function broadcastWith()
    {
        return json_item($this->message, 'Chat\Message');
    }
}
