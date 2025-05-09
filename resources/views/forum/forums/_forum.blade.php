{{--
    Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
    See the LICENCE file in the repository root for full licence text.
--}}
<li class="clickable-row forum-item">
    <div class="forum-item-stripe"><div class="forum-item-stripe__arrow"></div></div>

    <div class="forum-item__details">
        {!! link_to(
            route("forum.forums.show", $forum->forum_id),
            $forum->forum_name,
            ["class" => "forum-item__name clickable-row-link"]
        ) !!}
        <div class="forum-item__description">{{ $forum->forum_desc }}</div>

        @if (count($forum->subforums) > 0)
            <ul class="forum-item__subforums">
                @foreach($forum->subforums as $subforum)
                    <li class="forum-item__subforum">
                        <a href="{{ route("forum.forums.show", $subforum->forum_id) }}" title="{{ $subforum->forum_desc }}">
                            <i class="forum-item__subforum-icon fas fa-angle-right"></i>{{ $subforum->forum_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="forum-item__latest-post">
        @if (($lastTopic = $lastTopics[$forum->getKey()] ?? null) !== null)
            <a
                class="u-ellipsis-overflow"
                href="{{ post_url($lastTopic->topic_id, "unread", false) }}"
            >
                @if ($lastTopic->topic_replies > 0)
                    {{ osu_trans('forum.topic.reply_title_prefix') }}:
                @endif
                {{ $lastTopic->topic_title }}
            </a>

            <div>
                {!! osu_trans('forum.topic.latest_post', [
                    'when' => timeago($lastTopic->topic_last_post_time),
                    'user' => forum_user_link(
                        $lastTopic->topic_last_poster_id,
                        $lastTopic->topic_last_poster_name,
                        $lastTopic->topic_last_poster_colour,
                        $currentUserId,
                    ),
                ]) !!}
            </div>
        @endif
    </div>

    <div class="forum-item__arrow">
        <span class="fas fa-angle-right"></span>
    </div>
</li>
