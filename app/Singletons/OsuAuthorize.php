<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Singletons;

use App\Exceptions\AuthorizationCheckException;
use App\Libraries\AuthorizationResult;
use App\Libraries\Commentable;
use App\Models\Beatmap;
use App\Models\BeatmapDiscussion;
use App\Models\BeatmapDiscussionPost;
use App\Models\Beatmapset;
use App\Models\BeatmapsetEvent;
use App\Models\Chat\Channel;
use App\Models\Comment;
use App\Models\Contest;
use App\Models\Forum\Authorize as ForumAuthorize;
use App\Models\Forum\Forum;
use App\Models\Forum\PollOption;
use App\Models\Forum\Post;
use App\Models\Forum\Topic;
use App\Models\Forum\TopicCover;
use App\Models\Genre;
use App\Models\Language;
use App\Models\LegacyMatch\LegacyMatch;
use App\Models\Multiplayer\Room;
use App\Models\OAuth\Client;
use App\Models\Score\Best\Model as ScoreBest;
use App\Models\Solo;
use App\Models\Team;
use App\Models\TeamApplication;
use App\Models\Traits\ReportableInterface;
use App\Models\User;
use App\Models\UserContestEntry;
use App\Models\UserGroupEvent;
use Carbon\Carbon;
use Ds;

class OsuAuthorize
{
    const REQUEST_ATTRIBUTE_KEY = 'auth_map';
    const REQUEST_IS_INTEROP_KEY = 'interop_request';

    public static function alwaysCheck($ability)
    {
        static $set;

        $set ??= new Ds\Set([
            'ChannelPart',
            'ContestJudge',
            'IsNotOAuth',
            'IsOwnClient',
            'IsSpecialScope',
            'TeamApplicationAccept',
            'TeamApplicationStore',
            'TeamPart',
            'TeamStore',
            'TeamUpdate',
            'UserUpdateEmail',
        ]);

        return $set->contains($ability);
    }

    public function resetCache(): void
    {
        request()->attributes->remove(static::REQUEST_ATTRIBUTE_KEY);
    }

    public function doCheckUser(?User $user, string $ability, ?object $object = null): AuthorizationResult
    {
        $cacheKey = serialize([
            $ability,
            $user === null ? null : $user->getKey(),
            $object === null ? null : [$object->getTable(), $object->getKey()],
        ]);

        $authMap = request()->attributes->get(static::REQUEST_ATTRIBUTE_KEY);

        if ($authMap === null) {
            $authMap = new Ds\Map();
            request()->attributes->set(static::REQUEST_ATTRIBUTE_KEY, $authMap);
        }

        $auth = $authMap->get($cacheKey, null);

        if ($auth === null) {
            if ($user !== null && $user->isAdmin() && !static::alwaysCheck($ability)) {
                $message = 'ok';
            } else {
                $function = "check{$ability}";

                try {
                    $message = $this->$function($user, $object);
                } catch (AuthorizationCheckException $e) {
                    $message = $e->getMessage();
                }
            }

            $auth = new AuthorizationResult($message);
            $authMap->put($cacheKey, $auth);
        }

        return $auth;
    }

    public function checkBeatmapShow(?User $user, Beatmap $beatmap): string
    {
        if (!$beatmap->trashed()) {
            return 'ok';
        }

        if ($this->doCheckUser($user, 'BeatmapsetShow', $beatmap->beatmapset)->can()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmap
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapUpdateOwner(?User $user, ?Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        if ($beatmapset !== null) {
            $status = $beatmapset->approved;

            static $lovedModifiable = [
                Beatmapset::STATES['graveyard'],
                Beatmapset::STATES['loved'],
            ];
            if ($user->isProjectLoved() && in_array($status, $lovedModifiable, true)) {
                return 'ok';
            }

            static $ownerModifiable = [
                Beatmapset::STATES['wip'],
                Beatmapset::STATES['graveyard'],
                Beatmapset::STATES['pending'],
            ];
            if (
                in_array($status, $ownerModifiable, true)
                && !$beatmapset->hasNominations()
                && $beatmapset->user_id === $user->getKey()
            ) {
                return 'ok';
            }
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussion|null $discussion
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionAllowOrDenyKudosu(?User $user, ?BeatmapDiscussion $discussion): string
    {
        $this->ensureLoggedIn($user);

        if ($user->isBNG() || $user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussion $discussion
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionDestroy(?User $user, BeatmapDiscussion $discussion): string
    {
        $prefix = 'beatmap_discussion.destroy.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        if ($user->user_id !== $discussion->user_id) {
            return 'unauthorized';
        }

        if ($discussion->message_type === 'hype') {
            return $prefix.'is_hype';
        }

        if ($discussion->relationLoaded('beatmapDiscussionPosts')) {
            $visiblePosts = 0;

            foreach ($discussion->beatmapDiscussionPosts as $post) {
                if ($post->deleted_at !== null || $post->system) {
                    continue;
                }

                $visiblePosts++;

                if ($visiblePosts > 1) {
                    return $prefix.'has_reply';
                }
            }
        } elseif ($discussion->beatmapDiscussionPosts()->withoutTrashed()->withoutSystem()->count() > 1) {
            return $prefix.'has_reply';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionModerate(?User $user): string
    {
        $this->ensureLoggedIn($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussion $discussion
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionReopen(?User $user, BeatmapDiscussion $discussion): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussion $discussion
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionResolve(?User $user, BeatmapDiscussion $discussion): string
    {
        $prefix = 'beatmap_discussion.resolve.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($user->getKey() === $discussion->user_id) {
            return 'ok';
        }

        if (
            $discussion->beatmapset->approved !== Beatmapset::STATES['qualified']
            && $discussion->managedBy($user)
        ) {
            return 'ok';
        }

        if ($user->isModerator()) {
            return 'ok';
        }

        return $prefix.'not_owner';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussion $discussion
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionRestore(?User $user, BeatmapDiscussion $discussion): string
    {
        $this->ensureLoggedIn($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDiscussionReviewStore(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);
        $this->ensureHasPlayed($user);

        if ($beatmapset->discussion_locked || $beatmapset->downloadLimited()) {
            return 'beatmapset.discussion_locked';
        }

        return 'ok';
    }

    public function checkBeatmapDiscussionShow(?User $user, BeatmapDiscussion $discussion): string
    {
        if ($discussion->deleted_at === null) {
            if ($discussion->beatmap_id === null) {
                return 'ok';
            }

            if ($this->doCheckUser($user, 'BeatmapShow', $discussion->beatmap)->can()) {
                return 'ok';
            }
        }

        if ($user !== null && $user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussion $discussion
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionVote(?User $user, BeatmapDiscussion $discussion): string
    {
        $prefix = 'beatmap_discussion.vote.';

        if ($discussion->user !== null && $discussion->user->isBot()) {
            return $prefix.'bot';
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        static $votableStates = [
            Beatmapset::STATES['wip'],
            Beatmapset::STATES['pending'],
            Beatmapset::STATES['qualified'],
        ];

        if (!in_array($discussion->beatmapset->approved, $votableStates, true)) {
            if (!$user->isBNG() && !$user->isModerator()) {
                return $prefix.'wrong_beatmapset_state';
            }
        }

        if ($discussion->user_id === $user->user_id) {
            return $prefix.'owner';
        }

        if ($user->isBNG() || $user->isModerator()) {
            return 'ok';
        }

        // rate limit
        $recentVotesCount = $user
            ->beatmapDiscussionVotes()
            ->where('created_at', '>', Carbon::now()->subHours())
            ->count();

        if ($recentVotesCount > 60) {
            return $prefix.'limit_exceeded';
        }

        if ($discussion->userRecentVotesCount($user) >= 3) {
            return $prefix.'limit_exceeded';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussionPost $post
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionPostDestroy(?User $user, BeatmapDiscussionPost $post): string
    {
        $prefix = 'beatmap_discussion_post.destroy.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($post->system) {
            return $prefix.'system_generated';
        }

        if ($user->isModerator()) {
            return 'ok';
        }

        if ($user->user_id !== $post->user_id) {
            return $prefix.'not_owner';
        }

        if (!$post->canEdit()) {
            return $prefix.'resolved';
        }

        if ($post->beatmapDiscussion->beatmapset->discussion_locked) {
            return 'beatmapset.discussion_locked';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussionPost $post
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionPostEdit(?User $user, BeatmapDiscussionPost $post): string
    {
        $prefix = 'beatmap_discussion_post.edit.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($post->system) {
            return $prefix.'system_generated';
        }

        if ($user->user_id !== $post->user_id) {
            return $prefix.'not_owner';
        }

        if (!$post->canEdit()) {
            return $prefix.'resolved';
        }

        $beatmapset = $post->beatmapDiscussion->beatmapset;
        if ($beatmapset->discussion_locked || $beatmapset->downloadLimited()) {
            return 'beatmapset.discussion_locked';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussionPost $post
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapDiscussionPostRestore(?User $user, BeatmapDiscussionPost $post): string
    {
        $this->ensureLoggedIn($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkBeatmapDiscussionPostShow(?User $user, BeatmapDiscussionPost $post): string
    {
        if ($post->deleted_at === null) {
            return 'ok';
        }

        if ($user !== null && $user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkBeatmapsetAdvancedSearch(?User $user): string
    {
        if (oauth_token() === null && !$GLOBALS['cfg']['osu']['beatmapset']['guest_advanced_search']) {
            $this->ensureLoggedIn($user);
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDelete(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);

        if ($beatmapset->isGraveyard() && $user->getKey() === $beatmapset->user_id) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussion $discussion
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDiscussionNew(?User $user, BeatmapDiscussion $discussion): string
    {
        $beatmapsetDiscussionReplyPermission = $this->doCheckUser($user, 'BeatmapsetDiscussionReply', $discussion->beatmapset);
        if (!$beatmapsetDiscussionReplyPermission->can()) {
            return $beatmapsetDiscussionReplyPermission->rawMessage();
        }

        if ($discussion->message_type === 'mapper_note') {
            if ($discussion->managedBy($user)) {
                return 'ok';
            }

            if ($user->isModerator() || $user->isBNG()) {
                return 'ok';
            }

            // TODO: key should be changed.
            return 'beatmap_discussion.store.mapper_note_wrong_user';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param BeatmapDiscussionPost $post
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDiscussionReply(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);
        $this->ensureHasPlayed($user);

        // Moderators can't reply if download limited.
        if ($beatmapset->downloadLimited()) {
            return 'beatmapset.discussion_locked';
        }

        if ($user->isModerator()) {
            return 'ok';
        }

        if ($beatmapset->discussion_locked) {
            return 'beatmapset.discussion_locked';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetLove(?User $user): string
    {
        $this->ensureLoggedIn($user);

        if (!$user->isProjectLoved()) {
            return 'unauthorized';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetNominate(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        static $prefix = 'beatmap_discussion.nominate.';

        if (!$user->isBNG() && !$user->isNAT()) {
            return 'unauthorized';
        }

        if ($beatmapset->approved !== Beatmapset::STATES['pending']) {
            return $prefix.'incorrect_state';
        }

        $userId = $user->getKey();
        if ($userId === $beatmapset->user_id) {
            return $prefix.'owner';
        }

        $beatmapset->loadMissing('beatmaps.beatmapOwners');

        foreach ($beatmapset->beatmaps as $beatmap) {
            if ($beatmap->isOwner($user)) {
                return $prefix.'owner';
            }
        }

        if ($beatmapset->genre_id === Genre::UNSPECIFIED || $beatmapset->language_id === Language::UNSPECIFIED) {
            return $prefix.'set_metadata';
        }

        if ($user->beatmapsetNominationsToday() >= $GLOBALS['cfg']['osu']['beatmapset']['user_daily_nominations']) {
            return $prefix.'exhausted';
        }

        return 'ok';
    }

    public function checkBeatmapsetRemoveFromLoved(?User $user): string
    {
        // admin only (:
        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetResetNominations(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);

        if (!$user->isBNG() && !$user->isModerator()) {
            return 'unauthorized';
        }

        if ($beatmapset->approved !== Beatmapset::STATES['pending']) {
            return 'beatmap_discussion.nominate.incorrect_state';
        }

        return 'ok';
    }

    public function checkBeatmapsetShow(?User $user, Beatmapset $beatmapset): string
    {
        if (!$beatmapset->trashed()) {
            return 'ok';
        }

        if ($user !== null) {
            if ($user->isBNG() || $user->isModerator()) {
                return 'ok';
            }

            if ($user->getKey() === $beatmapset->user_id) {
                return 'ok';
            }
        }

        return 'unauthorized';
    }

    public function checkBeatmapsetShowDeleted(?User $user): string
    {
        if ($user !== null && $user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDescriptionEdit(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ((!$beatmapset->downloadLimited() && $user->getKey() === $beatmapset->user_id) || $user->isModerator()) {
            return 'ok';
        }

        return 'beatmapset_description.edit.not_owner';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDisqualify(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);

        if (!$user->isFullBN() && !$user->isModerator()) {
            return 'unauthorized';
        }

        if (!$beatmapset->isQualified()) {
            return 'beatmap_discussion.nominate.incorrect_state';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDiscussionLock(?User $user): string
    {
        $this->ensureLoggedIn($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param BeatmapsetEvent $event
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetEventViewUserId(?User $user, BeatmapsetEvent $event): string
    {
        if ($user !== null && $user->isModerator()) {
            return 'ok';
        }

        if (in_array($event->type, BeatmapsetEvent::types('public'), true)) {
            return 'ok';
        }

        if (in_array($event->type, BeatmapsetEvent::types('kudosuModeration'), true)) {
            if ($this->checkBeatmapDiscussionAllowOrDenyKudosu($user, null) === 'ok') {
                return 'ok';
            }
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetMetadataEdit(?User $user, Beatmapset $beatmapset): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        static $lovedEditable = [
            Beatmapset::STATES['graveyard'],
            Beatmapset::STATES['loved'],
        ];
        if ($user->isProjectLoved() && in_array($beatmapset->approved, $lovedEditable, true)) {
            return 'ok';
        }

        static $bnEditable = [
            Beatmapset::STATES['wip'],
            Beatmapset::STATES['pending'],
            Beatmapset::STATES['qualified'],
        ];
        if ($user->isBNG() && in_array($beatmapset->approved, $bnEditable, true)) {
            return 'ok';
        }

        static $ownerEditable = [
            Beatmapset::STATES['graveyard'],
            Beatmapset::STATES['wip'],
            Beatmapset::STATES['pending'],
        ];
        if ($user->getKey() !== $beatmapset->user_id || !in_array($beatmapset->approved, $ownerEditable, true)) {
            return 'unauthorized';
        }

        if ($beatmapset->hasNominations()) {
            return 'beatmapset.metadata.nominated';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Beatmapset $beatmapset
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkBeatmapsetDownload(?User $user, Beatmapset $beatmapset): string
    {
        // restricted users are still allowed to download
        $this->ensureLoggedIn($user);

        return 'ok';
    }

    public function checkBeatmapsetOffsetEdit(): string
    {
        return 'unauthorized';
    }

    public function checkBeatmapsetTagsEdit(?User $user): string
    {
        $this->ensureLoggedIn($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkChatAnnounce(?User $user): string
    {
        $prefix = 'chat.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user, $prefix);

        if ($user->isModerator() || $user->isChatAnnouncer()) {
            return 'ok';
        }

        return $prefix.'no_announce';
    }

    /**
     * @param User|null $user
     * @param Channel $channel
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkChatChannelCanMessage(?User $user, Channel $channel): string
    {
        $prefix = 'chat.';

        if ($channel->isAnnouncement()) {
            $chatBroadcastPermission = $this->doCheckUser($user, 'ChatAnnounce');

            return $chatBroadcastPermission->can() ? 'ok' : $chatBroadcastPermission->rawMessage();
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user, $prefix);

        if (!$GLOBALS['cfg']['osu']['user']['min_plays_allow_verified_bypass']) {
            $this->ensureHasPlayed($user);
        }

        if ($channel->isPM()) {
            $target = $channel->pmTargetFor($user);
            if ($target === null) {
                return $prefix.'no_channel';
            }

            $chatPmStartPermission = $this->doCheckUser($user, 'ChatPmStart', $target);
            if (!$chatPmStartPermission->can()) {
                return $chatPmStartPermission->rawMessage();
            }
        } else if (!$channel->exists) {
            return $prefix.'no_channel';
        }

        if ($user->isModerator()) {
            return 'ok';
        }

        if ($channel->moderated) {
            return $prefix.'moderated';
        }

        // TODO: add actual permission checks for bancho multiplayer games?
        if ($channel->isBanchoMultiplayerChat() && !request()->attributes->get(static::REQUEST_IS_INTEROP_KEY)) {
            return $prefix.'no_access';
        }

        return 'ok';
    }

    public function checkChatChannelListUsers(?User $user, Channel $channel): ?string
    {
        if ($channel->isAnnouncement() && $this->doCheckUser($user, 'ChatAnnounce', $channel)->can()) {
            return 'ok';
        }

        return null;
    }

    /**
     * TODO: always use a channel for this check?
     *
     * @param User|null $user
     * @param User $target
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkChatPmStart(?User $user, User $target): string
    {
        $prefix = 'chat.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user, $prefix);

        if (!$GLOBALS['cfg']['osu']['user']['min_plays_allow_verified_bypass']) {
            $this->ensureHasPlayed($user);
        }

        if ($user->pm_friends_only && !$user->hasFriended($target)) {
            return $prefix.'receive_friends_only';
        }

        if ($user->isModerator() || $user->isBot()) {
            return 'ok';
        }

        if ($target->hasBlocked($user) || $user->hasBlocked($target)) {
            return $prefix.'blocked';
        }

        if ($target->pm_friends_only && !$target->hasFriended($user)) {
            return $prefix.'friends_only';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Channel $channel
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkChatChannelSend(?User $user, Channel $channel): string
    {
        $prefix = 'chat.';

        $this->ensureLoggedIn($user);
        $this->ensureSessionVerified($user);
        $this->ensureCleanRecord($user, $prefix);
        // This check becomes useless when min_plays_allow_verified_bypass is enabled.
        $this->ensureHasPlayed($user);

        if (!$this->doCheckUser($user, 'ChatChannelRead', $channel)->can()) {
            return $prefix.'no_access';
        }

        $canMessagePermission = $this->doCheckUser($user, 'ChatChannelCanMessage', $channel);
        if (!$canMessagePermission->can()) {
            return $canMessagePermission->rawMessage();
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Channel $channel
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkChatChannelRead(?User $user, Channel $channel): string
    {
        $prefix = 'chat.';

        $this->ensureLoggedIn($user);

        // FIXME: this should be eventually removed and users should have their respective UserChannel entry
        if (!$channel->isPM() && request()->attributes->get(static::REQUEST_IS_INTEROP_KEY)) {
            return 'ok';
        }

        if ($channel->hasUser($user)) {
            return 'ok';
        }

        return $prefix.'no_access';
    }

    /**
     * @param User|null $user
     * @param Channel $channel
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkChatChannelJoin(?User $user, Channel $channel): ?string
    {
        $prefix = 'chat.';

        $this->ensureLoggedIn($user);

        if ($channel->isPublic()) {
            return 'ok';
        }

        $this->ensureCleanRecord($user, $prefix);

        // joining multiplayer room is done through room endpoint
        // team channel handling is done through team model
        if ($channel->isMultiplayer() || $channel->isTeam()) {
            return null;
        }

        // allow joining of 'tournament' matches (for lazer/tournament client)
        if (optional($channel->multiplayerMatch)->isTournamentMatch()) {
            return 'ok';
        }

        return $prefix.'no_access';
    }

    /**
     * @param User|null $user
     * @param Channel $channel
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkChatChannelPart(?User $user, Channel $channel): string
    {
        $prefix = 'chat.';

        $this->ensureLoggedIn($user);

        // team channel handling is done through team model
        if (!$channel->isTeam() && $channel->type !== Channel::TYPES['private']) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Comment $comment
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkCommentDestroy(?User $user, Comment $comment): string
    {
        if ($this->doCheckUser($user, 'CommentModerate')->can()) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($comment->user_id === $user->getKey()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkCommentModerate(?User $user): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkCommentRestore(?User $user, Comment $comment): string
    {
        if ($this->doCheckUser($user, 'CommentModerate')->can()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkCommentShow(?User $user, Comment $comment): string
    {
        if ($this->doCheckUser($user, 'CommentModerate')->can()) {
            return 'ok';
        }

        if (!$comment->trashed()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Comment $comment
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkCommentStore(?User $user, Commentable $commentable): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);
        $this->ensureHasPlayed($user);

        if ($commentable->commentLocked()) {
            return 'comment.store.disabled';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Comment $comment
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkCommentUpdate(?User $user, Comment $comment): string
    {
        if ($this->doCheckUser($user, 'CommentModerate')->can()) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($comment->user_id === $user->getKey()) {
            if ($comment->trashed()) {
                return 'comment.update.deleted';
            }

            $commentable = $comment->commentable;
            if ($commentable instanceof Beatmapset && $commentable->downloadLimited()) {
                return 'comment.store.disabled';
            }

            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Comment $comment
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkCommentVote(?User $user, Comment $comment): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($comment->user_id === $user->getKey()) {
            return 'unauthorized';
        }

        return 'ok';
    }

    /**
     * @throws AuthorizationCheckException
     */
    public function checkCommentPin(?User $user, Comment $comment): string
    {
        $this->ensureLoggedIn($user);

        if (!$comment->commentable instanceof Beatmapset) {
            return 'unauthorized';
        }

        if (!$comment->pinned && $comment->commentable->comments()->pinned()->exists()) {
            return 'unauthorized';
        }

        if ($this->doCheckUser($user, 'CommentModerate')->can()) {
            return 'ok';
        }

        $this->ensureCleanRecord($user);

        if (
            $comment->user_id === $user->getKey() &&
            $comment->commentable->user_id === $user->getKey()
        ) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Contest $contest
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkContestEntryStore(?User $user, Contest $contest): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$contest->isSubmissionOpen()) {
            return 'contest.entry.over';
        }

        $currentEntries = UserContestEntry::where(['contest_id' => $contest->id, 'user_id' => $user->user_id])->count();
        if ($currentEntries >= $contest->max_entries) {
            return 'contest.entry.limit_reached';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param UserContestEntry $contestEntry
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkContestEntryDestroy(?User $user, UserContestEntry $contestEntry): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($contestEntry->user_id !== $user->user_id) {
            return 'unauthorized';
        }

        if (!$contestEntry->contest->isSubmissionOpen()) {
            return 'contest.entry.over';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Contest $contest
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkContestJudge(?User $user, Contest $contest): string
    {
        $this->ensureLoggedIn($user);

        if (!$contest->isJudgingActive()) {
            return 'contest.judging_not_active';
        }

        if (!$contest->isJudge($user)) {
            return 'unauthorized';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Contest $contest
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkContestJudgeShow(?User $user, Contest $contest): string
    {
        // so that admins can show the panel but not vote (ContestJudge is alwaysCheck)
        return $this->checkContestJudge($user, $contest);
    }

    /**
     * @param User|null $user
     * @param Contest $contest
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkContestVote(?User $user, Contest $contest): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$contest->isVotingOpen()) {
            return 'contest.voting.over';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Forum $forum
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumModerate(?User $user, Forum $forum): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        if ($forum->moderator_groups !== null && !empty(array_intersect($user->groupIds()['active'], $forum->moderator_groups))) {
            return 'ok';
        }

        return 'forum.moderate.no_permission';
    }

    public function checkForumView(?User $user, Forum $forum): string
    {
        if ($this->doCheckUser($user, 'ForumModerate', $forum)->can()) {
            return 'ok';
        }

        if ($forum->categoryId() !== $GLOBALS['cfg']['osu']['forum']['admin_forum_id']) {
            return 'ok';
        }

        return 'forum.view.admin_only';
    }

    /**
     * @param User|null $user
     * @param Post $post
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumPostDelete(?User $user, Post $post): string
    {
        $prefix = 'forum.post.delete.';

        if ($this->doCheckUser($user, 'ForumModerate', $post->forum)->can()) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$this->doCheckUser($user, 'ForumView', $post->forum)->can()) {
            return $prefix.'no_forum_access';
        }

        if ($post->poster_id !== $user->user_id) {
            return $prefix.'not_owner';
        }

        if ($post->topic->isLocked()) {
            return $prefix.'locked';
        }

        // This check is assumed to be the last one when checking for
        // button display in forum.topics._posts view.
        if ($post->getKey() !== $post->topic->topic_last_post_id) {
            return $prefix.'only_last_post';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Post $post
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumPostEdit(?User $user, Post $post): string
    {
        $prefix = 'forum.post.edit.';

        $forum = $post->forum;
        if ($this->doCheckUser($user, 'ForumModerate', $forum)->can()) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$this->doCheckUser($user, 'ForumView', $forum)->can()) {
            return $prefix.'no_forum_access';
        }

        if ($post->poster_id !== $user->user_id) {
            return $prefix.'not_owner';
        }

        if ($post->trashed()) {
            return $prefix.'deleted';
        }

        if ($post->topic->isLocked()) {
            return $prefix.'topic_locked';
        }

        if ($post->post_edit_locked) {
            return $prefix.'locked';
        }

        $isFirstPost = $post->getKey() === $post->topic->topic_first_post_id;
        if (!ForumAuthorize::aclCheck($user, $isFirstPost ? 'f_post' : 'f_reply', $forum)) {
            return $prefix.'no_permission';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Forum $forum
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumPostStore(?User $user, Forum $forum): string
    {
        $prefix = 'forum.post.store.';

        if ($this->doCheckUser($user, 'ForumModerate', $forum)->can()) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$user->isBot()) {
            $plays = $user->playCount();
            $posts = $user->user_posts;
            $forInitialHelpForum = in_array($forum->forum_id, $GLOBALS['cfg']['osu']['forum']['initial_help_forum_ids'], true);

            if ($forInitialHelpForum) {
                if ($plays < 10 && $posts > 10) {
                    return $prefix.'too_many_help_posts';
                }
            } else {
                if ($plays < $GLOBALS['cfg']['osu']['forum']['minimum_plays'] && $plays < $posts + 1) {
                    return $prefix.'play_more';
                }

                $this->ensureHasPlayed($user);
            }
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Topic $topic
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicDelete(?User $user, Topic $topic): string
    {
        return $this->checkForumPostDelete($user, $topic->firstPost);
    }

    /**
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicEdit(?User $user, Topic $topic): string
    {
        return $this->checkForumPostEdit($user, $topic->firstPost);
    }

    /**
     * @param User|null $user
     * @param Topic $topic
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicReply(?User $user, Topic $topic): string
    {
        $prefix = 'forum.topic.reply.';

        if ($this->doCheckUser($user, 'ForumModerate', $topic->forum)->can()) {
            return 'ok';
        }

        $this->ensureLoggedIn($user, $prefix.'user.');
        $this->ensureCleanRecord($user, $prefix.'user.');

        if (!$this->doCheckUser($user, 'ForumView', $topic->forum)->can()) {
            return $prefix.'no_forum_access';
        }

        $postStorePermission = $this->doCheckUser($user, 'ForumPostStore', $topic->forum);

        if (!$postStorePermission->can()) {
            return $postStorePermission->rawMessage();
        }

        if (!ForumAuthorize::aclCheck($user, 'f_reply', $topic->forum)) {
            return $prefix.'no_permission';
        }

        if ($topic->isLocked()) {
            return $prefix.'locked';
        }

        if (!$topic->allowsDoublePosting() && $topic->isDoublePostBy($user)) {
            return $prefix.'double_post';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Forum $forum
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicStore(?User $user, Forum $forum): string
    {
        $prefix = 'forum.topic.store.';

        if ($this->doCheckUser($user, 'ForumModerate', $forum)->can()) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$this->doCheckUser($user, 'ForumView', $forum)->can()) {
            return $prefix.'no_forum_access';
        }

        $postStorePermission = $this->doCheckUser($user, 'ForumPostStore', $forum);

        if (!$postStorePermission->can()) {
            return $postStorePermission->rawMessage();
        }

        if (!$forum->isOpen()) {
            return $prefix.'forum_closed';
        }

        if (!ForumAuthorize::aclCheck($user, 'f_post', $forum)) {
            return $prefix.'no_permission';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Topic $topic
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicWatch(?User $user, Topic $topic): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$this->doCheckUser($user, 'ForumView', $topic->forum)->can()) {
            return 'forum.topic.watch.no_forum_access';
        }

        return 'ok';
    }

    /**
     * @param  User|null $user
     * @param  Topic|TopicCover $object
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicCoverEdit(?User $user, /* Topic|TopicCover */ $object): string
    {
        $prefix = 'forum.topic_cover.edit.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        $topic = $object instanceof Topic ? $object : $object->topic;

        if ($topic !== null) {
            $forumTopicCoverStorePermission = $this->doCheckUser($user, 'ForumTopicCoverStore', $topic->forum);
            if (!$forumTopicCoverStorePermission->can()) {
                return $forumTopicCoverStorePermission->rawMessage();
            }

            return $this->checkForumTopicEdit($user, $topic);
        }

        if ($object->owner() === null) {
            return $prefix.'uneditable';
        }

        if ($object->owner()->user_id !== $user->user_id) {
            return $prefix.'not_owner';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Forum $forum
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicCoverStore(?User $user, Forum $forum): string
    {
        $prefix = 'forum.topic_cover.store.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$forum->allow_topic_covers && !$this->doCheckUser($user, 'ForumModerate', $forum)->can()) {
            return $prefix.'forum_not_allowed';
        }

        return 'ok';
    }

    public function checkForumTopicPollEdit(?User $user, Topic $topic): string
    {
        if ($this->doCheckUser($user, 'ForumModerate', $topic->forum)->can()) {
            return 'ok';
        }

        $forumTopicStorePermission = $this->doCheckUser($user, 'ForumTopicStore', $topic->forum);
        if (!$forumTopicStorePermission->can()) {
            return $forumTopicStorePermission->rawMessage();
        }

        if ($topic->topic_poster === $user->user_id) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkForumTopicPollOptionShowResult(?User $user, PollOption $pollOption): string
    {
        return $this->doCheckUser($user, 'ForumTopicPollShowResults', $pollOption->topic)->rawMessage() ?? 'ok';
    }

    /**
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicPollShowResults(?User $user, Topic $topic): string
    {
        if (!$topic->poll_hide_results || ($topic->pollEnd()?->isPast() ?? true)) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);

        $isNotOAuthPermission = $this->doCheckUser($user, 'IsNotOAuth');
        if (!$isNotOAuthPermission->can()) {
            return $isNotOAuthPermission->rawMessage();
        }

        if ($this->doCheckUser($user, 'ForumModerate', $topic->forum)->can()) {
            return 'ok';
        }

        if ($topic->topic_poster === $user->getKey()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param Topic $topic
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkForumTopicVote(?User $user, Topic $topic): string
    {
        $prefix = 'forum.topic.vote.';

        if (!$topic->poll()->isOpen()) {
            return $prefix.'over';
        }

        $this->ensureLoggedIn($user, $prefix.'user.');
        $this->ensureCleanRecord($user, $prefix.'user.');

        if (!$this->doCheckUser($user, 'ForumView', $topic->forum)->can()) {
            return $prefix.'no_forum_access';
        }

        $plays = $user->playCount();
        if ($plays < $GLOBALS['cfg']['osu']['forum']['minimum_plays']) {
            return $prefix.'play_more';
        }

        if (!$topic->poll_vote_change) {
            if ($topic->poll()->votedBy($user)) {
                return $prefix.'voted';
            }
        }

        return 'ok';
    }

    public function checkIsOwnClient(?User $user, Client $client): string
    {
        if ($user === null || $user->getKey() !== $client->user_id) {
            return 'unauthorized';
        }

        return 'ok';
    }

    public function checkIsNotOAuth(?User $user): string
    {
        // TODO: add test that asserts oauth_token is always set if user()->token() exists.
        if (oauth_token() === null) {
            return 'ok';
        }

        return 'unauthorized';
    }

    // Allow non-OAuth requests or OAuth requests with * scope.
    public function checkIsSpecialScope(?User $user): string
    {
        if ($user === null) {
            return 'unauthorized';
        }

        $token = $user->token();
        if ($token === null || $token->can('*')) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkLegacyIrcKeyStore(?User $user): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        // isBot checks user primary group
        if (!$user->isGroup(app('groups')->byIdentifier('bot')) && $user->playCount() < 100) {
            return 'play_more';
        }

        return 'ok';
    }

    public function checkNewsIndexUpdate(?User $user): string
    {
        // yet another admin only =D
        return 'unauthorized';
    }

    public function checkNewsPostUpdate(?User $user): string
    {
        // yet another admin only =D
        return 'unauthorized';
    }

    public function checkLegacyApiKeyStore(?User $user): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        return 'ok';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkLivestreamPromote(?User $user): string
    {
        $this->ensureLoggedIn($user);

        if ($user->isModerator()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkMultiplayerRoomCreate(?User $user): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param Room $room
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkMultiplayerRoomDestroy(?User $user, Room $room): string
    {
        $prefix = 'room.destroy.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($room->user_id !== $user->getKey()) {
            return $prefix.'not_owner';
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkMultiplayerScoreSubmit(?User $user, Room $room): string
    {
        $this->ensureLoggedIn($user);

        if ($room->isRealtime()) {
            $this->ensureCleanRecord($user);
        } else {
            if ($user->isRestricted()) {
                throw new AuthorizationCheckException('restricted');
            }
        }

        return 'ok';
    }

    /**
     * @param User|null $user
     * @param \App\Models\Score\Best\Model $score
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkScorePin(?User $user, ScoreBest|Solo\Score $score): string
    {
        $prefix = 'score.pin.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($score->user_id !== $user->getKey()) {
            return $prefix.'not_owner';
        }

        if (!$score->passed) {
            return $prefix.'failed';
        }

        $pinned = $user->scorePins()->forRuleset($score->getMode())->withVisibleScore()->count();

        if ($pinned >= $user->maxScorePins()) {
            return $prefix.'too_many';
        }

        return 'ok';
    }

    public function checkTeamApplicationAccept(?User $user, TeamApplication $application): ?string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        $team = $application->team;

        if ($team->leader_id !== $user->getKey()) {
            return null;
        }
        if ($team->emptySlots() < 1) {
            return 'team.application.store.team_full';
        }

        return 'ok';
    }

    public function checkTeamApplicationStore(?User $user, Team $team): ?string
    {
        $prefix = 'team.application.store.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if ($user->team !== null) {
            return $user->team->getKey() === $team->getKey()
                ? $prefix.'already_member'
                : $prefix.'already_other_member';
        }
        if ($user->teamApplication()->exists()) {
            return $prefix.'currently_applying';
        }
        if (!$team->is_open) {
            return $prefix.'team_closed';
        }
        if ($team->emptySlots() < 1) {
            return $prefix.'team_full';
        }

        return 'ok';
    }

    public function checkTeamPart(?User $user, Team $team): ?string
    {
        $this->ensureLoggedIn($user);

        $prefix = 'team.part.';

        if ($team->leader_id === $user->getKey()) {
            return $prefix.'is_leader';
        }
        if ($team->getKey() !== $user?->team?->getKey()) {
            return $prefix.'not_member';
        }

        return 'ok';
    }

    public function checkTeamStore(?User $user): ?string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);
        $this->ensureHasPlayed($user);

        if ($GLOBALS['cfg']['osu']['team']['create_require_supporter'] && !$user->isSupporter()) {
            return 'team.store.require_supporter_tag';
        }

        if ($user->team !== null) {
            return 'team.application.store.already_other_member';
        }

        if ($user->teamApplication !== null) {
            return 'team.application.store.currently_applying';
        }

        return 'ok';
    }

    public function checkTeamUpdate(?User $user, Team $team): ?string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        return $team->leader_id === $user->getKey() ? 'ok' : null;
    }

    public function checkUserGroupEventShowActor(?User $user, UserGroupEvent $event): string
    {
        if ($user?->isGroup($event->group)) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkUserGroupEventShowAll(?User $user): string
    {
        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @param User $pageOwner
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkUserPageEdit(?User $user, User $pageOwner): string
    {
        $prefix = 'user.page.edit.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        $page = $pageOwner->userPage;

        if ($page === null) {
            if (!$user->hasSupported()) {
                return $prefix.'require_supporter_tag';
            }
        } else {
            if ($user->isModerator()) {
                return 'ok';
            }

            if ($user->getKey() !== $page->poster_id) {
                return $prefix.'not_owner';
            }

            // Some user pages (posts) are orphaned and don't have parent topic.
            if ($page->post_edit_locked || optional($page->topic)->isLocked() ?? false) {
                return $prefix.'locked';
            }
        }

        return 'ok';
    }

    public function checkUserReport(?User $user, ReportableInterface $model): string
    {
        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$model->trashed()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkUserShow(?User $user, User $owner): string
    {
        $prefix = 'user.show.';

        if ($user !== null && $user->user_id === $owner->user_id) {
            return 'ok';
        }

        if ($owner->hasProfileVisible()) {
            return 'ok';
        } else {
            return $prefix.'no_access';
        }
    }

    public function checkUserShowRestrictedStatus(?User $user, User $owner): string
    {
        if ($this->doCheckUser($user, 'IsNotOAuth')->can()) {
            return 'ok';
        }

        if ($user !== null && $user->getKey() === $owner->getKey()) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkUserUpdateEmail(?User $user): ?string
    {
        $this->ensureLoggedIn($user);

        return $user->lock_email_changes
            ? 'user.update_email.locked'
            : 'ok';
    }

    /**
     * @param User|null $user
     * @param LegacyMatch $match
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkMatchView(?User $user, LegacyMatch $match): string
    {
        if (!$match->private) {
            return 'ok';
        }

        $this->ensureLoggedIn($user);

        if ($user->isModerator() || $match->hadPlayer($user)) {
            return 'ok';
        }

        return 'unauthorized';
    }

    public function checkUserCoverPresetManage(?User $user): ?string
    {
        return null;
    }

    public function checkUserSilenceShowExtendedInfo(?User $user): string
    {
        // admin only, i guess =D
        return 'unauthorized';
    }

    /**
     * @param User|null $user
     * @return string
     * @throws AuthorizationCheckException
     */
    public function checkWikiPageRefresh(?User $user): string
    {
        $this->ensureLoggedIn($user);

        // yet another admin only =D
        return 'unauthorized';
    }

    public function checkBeatmapTagStore(?User $user, Beatmap $beatmap): string
    {
        $prefix = 'beatmap_tag.store.';

        $this->ensureLoggedIn($user);
        $this->ensureCleanRecord($user);

        if (!$user->soloScores()->where('beatmap_id', $beatmap->getKey())->exists()) {
            return $prefix.'no_score';
        }

        return 'ok';
    }

    /**
     * @throws AuthorizationCheckException
     */
    public function ensureLoggedIn(?User $user, string $prefix = ''): void
    {
        if ($user === null) {
            throw new AuthorizationCheckException($prefix.'require_login');
        }
    }

    /**
     * @throws AuthorizationCheckException
     */
    private function ensureCleanRecord(User $user, string $prefix = ''): void
    {
        if ($user->isRestricted()) {
            throw new AuthorizationCheckException($prefix.'restricted');
        }

        if ($user->isSilenced()) {
            throw new AuthorizationCheckException($prefix.'silenced');
        }
    }

    /**
     * @throws AuthorizationCheckException
     */
    private function ensureHasPlayed(User $user): void
    {
        if ($user->isBot()) {
            return;
        }

        $minPlays = $GLOBALS['cfg']['osu']['user']['min_plays_for_posting'];

        if ($user->playCount() >= $minPlays) {
            return;
        }

        if ($GLOBALS['cfg']['osu']['user']['min_plays_allow_verified_bypass']) {
            if ($user->isSessionVerified()) {
                return;
            }

            throw new AuthorizationCheckException('require_verification');
        }

        throw new AuthorizationCheckException('play_more');
    }

    /**
     * Ensure User is logged in and verified.
     *
     * @throws AuthorizationCheckException
     */
    private function ensureSessionVerified(User $user)
    {
        if (!$user->isSessionVerified()) {
            throw new AuthorizationCheckException('require_verification');
        }
    }
}
