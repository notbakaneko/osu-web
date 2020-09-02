<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Transformers\Chat;

use App\Models\Chat\Channel;
use App\Models\Chat\UserChannel;
use App\Transformers\TransformerAbstract;

class UserChannelTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'first_message_id',
        'last_message_id',
        'recent_messages',
        'users',
    ];

    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function transform(UserChannel $userChannel)
    {
        $channel = $userChannel->channel;

        return [
            'channel_id' => $channel->channel_id,
            'description' => $channel->description,
            'last_read_id' => $userChannel->last_read_id,
            'moderated' => $channel->moderated,
            'name' => $channel->name,
            'type' => $channel->type,
        ];
    }
}
