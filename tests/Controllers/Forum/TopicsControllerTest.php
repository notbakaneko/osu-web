<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Tests\Controllers\Forum;

use App\Exceptions\InvalidScopeException;
use App\Models\Forum\Authorize;
use App\Models\Forum\Forum;
use App\Models\Forum\Post;
use App\Models\Forum\Topic;
use App\Models\Forum\TopicTrack;
use App\Models\OAuth\Client;
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TopicsControllerTest extends TestCase
{
    public static function dataProviderForTestLockClientCredentials(): array
    {
        // $groups, $forumGroup, $expectException, $success
        return [
            [[], null, true, false],
            [['gmt'], null, true, false],
            [['gmt'], 'gmt', true, false],
            [['bot'], null, false, false],
            [['gmt', 'bot'], null, false, false],
            [['gmt', 'bot'], 'gmt', false, true],
        ];
    }

    public static function dataProviderForTestPinClientCredentials(): array
    {
        // $groups, $forumGroup, $expectException, $success
        return [
            [[], null, true, false],
            [['gmt'], null, true, false],
            [['gmt'], 'gmt', true, false],
            [['bot'], null, false, false],
            [['gmt', 'bot'], null, false, false],
            [['gmt', 'bot'], 'gmt', false, true],
        ];
    }

    public function testDestroy(): void
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->withPost()->create(['topic_poster' => $user]);

        $this->expectCountChange(fn () => Topic::count(), -1);

        $this
            ->actingAsVerified($user)
            ->delete(route('forum.topics.destroy', $topic))
            ->assertRedirect(route('forum.forums.show', $topic->forum_id));
    }

    public function testDestroyAsDifferentUser(): void
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->withPost()->create();

        $this->expectCountChange(fn () => Topic::count(), 0);

        $this
            ->actingAsVerified($user)
            ->delete(route('forum.topics.destroy', $topic))
            ->assertStatus(403);
    }

    public function testDestroyAsGuest(): void
    {
        $topic = Topic::factory()->withPost()->create();

        $this->expectCountChange(fn () => Topic::count(), 0);

        $this
            ->delete(route('forum.topics.destroy', $topic))
            ->assertStatus(401);
    }

    public function testDestroyAsModerator(): void
    {
        $topic = Topic::factory()->withPost()->create();
        $user = User::factory()->withGroup('gmt')->create();

        $this->expectCountChange(fn () => Topic::count(), -1);

        $this
            ->actingAsVerified($user)
            ->delete(route('forum.topics.destroy', $topic))
            ->assertSuccessful();
    }

    public function testLock(): void
    {
        $user = User::factory()->withGroup('gmt')->create();
        $topic = Topic::factory()->create();

        $this
            ->actingAsVerified($user)
            ->post(route('forum.topics.lock', $topic), ['lock' => true])
            ->assertSuccessful();

        $this->assertTrue($topic->fresh()->isLocked());
    }

    public function testLockAuthCode(): void
    {
        $user = User::factory()->withGroup('gmt')->create();
        $client = Client::factory()->create(['user_id' => $user]);
        $topic = Topic::factory()->create();

        $this
            ->actAsScopedUser($user, ['forum.write_manage'], $client)
            ->post(route('api.forum.topics.lock', $topic), ['lock' => true])
            ->assertStatus(403);
    }

    #[DataProvider('dataProviderForTestLockClientCredentials')]
    public function testLockClientCredentials(array $groups, ?string $forumGroup, bool $expectException, bool $success): void
    {
        $user = User::factory();
        foreach ($groups as $group) {
            $user = $user->withGroup($group);
        }
        $user = $user->create();
        $client = Client::factory()->create(['user_id' => $user]);

        $topic = Topic::factory()->create();
        if ($forumGroup !== null) {
            $topic->forum->update(['moderator_groups' => [app('groups')->byIdentifier($forumGroup)->getKey()]]);
        }

        if ($expectException) {
            $this->expectException(InvalidScopeException::class);
        }

        $subject = $this
            ->actAsScopedUser(null, ['delegate', 'forum.write_manage'], $client)
            ->post(route('api.forum.topics.lock', $topic), ['lock' => true]);

        if ($success) {
            $subject->assertSuccessful();
        } else {
            $subject->assertStatus(403);
        }
    }

    public function testPin(): void
    {
        $user = User::factory()->withGroup('gmt')->create();
        $topic = Topic::factory()->create();
        $typeInt = Topic::TYPES['sticky'];

        $this
            ->actingAsVerified($user)
            ->post(route('forum.topics.pin', $topic), ['pin' => $typeInt])
            ->assertSuccessful();

        $this->assertSame($typeInt, $topic->fresh()->topic_type);
    }

    public function testPinAuthCode(): void
    {
        $user = User::factory()->withGroup('gmt')->create();
        $client = Client::factory()->create(['user_id' => $user]);
        $topic = Topic::factory()->create();

        $this
            ->actAsScopedUser($user, ['forum.write_manage'], $client)
            ->post(route('api.forum.topics.pin', $topic), ['pin' => Topic::TYPES['sticky']])
            ->assertStatus(403);
    }

    #[DataProvider('dataProviderForTestPinClientCredentials')]
    public function testPinClientCredentials(array $groups, ?string $forumGroup, bool $expectException, bool $success): void
    {
        $user = User::factory();
        foreach ($groups as $group) {
            $user = $user->withGroup($group);
        }
        $user = $user->create();
        $client = Client::factory()->create(['user_id' => $user]);

        $topic = Topic::factory()->create();
        if ($forumGroup !== null) {
            $topic->forum->update(['moderator_groups' => [app('groups')->byIdentifier($forumGroup)->getKey()]]);
        }

        if ($expectException) {
            $this->expectException(InvalidScopeException::class);
        }

        $subject = $this
            ->actAsScopedUser(null, ['delegate', 'forum.write_manage'], $client)
            ->post(route('api.forum.topics.pin', $topic), ['pin' => Topic::TYPES['sticky']]);

        if ($success) {
            $subject->assertSuccessful();
        } else {
            $subject->assertStatus(403);
        }
    }

    public function testReply(): void
    {
        $topic = Topic::factory()->create();
        $user = User::factory()->withPlays($GLOBALS['cfg']['osu']['forum']['minimum_plays'])->create();
        Authorize::factory()->reply()->create([
            'forum_id' => $topic->forum_id,
            'group_id' => app('groups')->byIdentifier('default'),
        ]);

        $this->expectCountChange(fn () => Post::count(), 1);
        $this->expectCountChange(fn () => Topic::count(), 0);
        $this->expectCountChange(fn () => $topic->fresh()->postCount(), 1);

        $this
            ->actingAsVerified($user)
            ->post(route('forum.topics.reply', $topic), [
                'body' => 'This is test reply',
            ])
            ->assertSuccessful();
    }

    public function testReplyWithoutPlays(): void
    {
        $topic = Topic::factory()->create();
        $user = User::factory()->create();
        Authorize::factory()->reply()->create([
            'forum_id' => $topic->forum_id,
            'group_id' => app('groups')->byIdentifier('default'),
        ]);

        $this->expectCountChange(fn () => Post::count(), 0);
        $this->expectCountChange(fn () => Topic::count(), 0);
        $this->expectCountChange(fn () => $topic->fresh()->postCount(), 0);

        $this
            ->actingAsVerified($user)
            ->post(route('forum.topics.reply', $topic), [
                'body' => 'This is test reply',
            ])
            ->assertStatus(403);
    }

    public function testRestore(): void
    {
        $moderator = User::factory()->withGroup('gmt')->create();
        $topic = Topic::factory()->withPost()->create();
        $topic->delete();

        $this->expectCountChange(fn () => Topic::count(), 1);

        $this
            ->actingAsVerified($moderator)
            ->post(route('forum.topics.restore', $topic))
            ->assertSuccessful();
    }

    public function testShow(): void
    {
        $topic = Topic::factory()->withPost()->create();

        $this
            ->get(route('forum.topics.show', $topic))
            ->assertSuccessful();
    }

    public function testShowMissingFirstPost(): void
    {
        $topic = Topic::factory()->withPost()->create();
        $topic->update(['topic_first_post_id' => 0]);

        $this
            ->get(route('forum.topics.show', $topic))
            ->assertStatus(404);
    }

    public function testShowNoMorePosts(): void
    {
        $topic = Topic::factory()->withPost()->create();

        $this
            ->get(route('forum.topics.show', [
                'start' => $topic->topic_first_post_id + 1,
                'topic' => $topic,
            ]))
            ->assertStatus(302);
    }

    public function testShowNoMorePostsWithSkipLayout(): void
    {
        $topic = Topic::factory()->withPost()->create();

        $this
            ->get(route('forum.topics.show', [
                'skip_layout' => 1,
                'start' => $topic->topic_first_post_id + 1,
                'topic' => $topic,
            ]))
            ->assertStatus(204);
    }

    public function testShowMissingPosts(): void
    {
        $topic = Topic::factory()->create();

        $this
            ->get(route('forum.topics.show', $topic))
            ->assertStatus(404);
    }

    public function testShowNewUser(): void
    {
        $topic = Topic::factory()->withPost()->create();
        $user = User::factory()->create();

        $this
            ->be($user)
            ->get(route('forum.topics.show', $topic))
            ->assertSuccessful();
    }

    public function testStore(): void
    {
        $forum = Forum::factory()->create();
        $user = User::factory()->withPlays($GLOBALS['cfg']['osu']['forum']['minimum_plays'])->create();
        Authorize::factory()->post()->create([
            'forum_id' => $forum,
            'group_id' => app('groups')->byIdentifier('default'),
        ]);

        $this->expectCountChange(fn () => Post::count(), 1);
        $this->expectCountChange(fn () => Topic::count(), 1);
        $this->expectCountChange(fn () => TopicTrack::count(), 1);

        $this
            ->actingAsVerified($user)
            ->post(route('forum.topics.store', ['forum_id' => $forum]), [
                'title' => 'Test post',
                'body' => 'This is test post',
            ])
            ->assertRedirect(route(
                'forum.topics.show',
                Topic::orderBy('topic_id', 'DESC')->first(),
            ));
    }

    public function testStoreWithoutPlays(): void
    {
        $forum = Forum::factory()->create();
        $user = User::factory()->create();
        Authorize::factory()->post()->create([
            'forum_id' => $forum,
            'group_id' => app('groups')->byIdentifier('default'),
        ]);

        $this->expectCountChange(fn () => Post::count(), 0);
        $this->expectCountChange(fn () => Topic::count(), 0);
        $this->expectCountChange(fn () => TopicTrack::count(), 0);

        $this
            ->actingAsVerified($user)
            ->post(route('forum.topics.store', ['forum_id' => $forum]), [
                'title' => 'Test post',
                'body' => 'This is test post',
            ])
            ->assertStatus(403);
    }

    public function testUpdateTitle(): void
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->withPost()->create([
            'topic_poster' => $user,
            'topic_title' => 'Initial title',
        ]);
        $newTitle = 'A different title';

        $this
            ->actingAsVerified($user)
            ->put(route('forum.topics.update', $topic), [
                'forum_topic' => [
                    'topic_title' => $newTitle,
                ],
            ])
            ->assertSuccessful();

        $this->assertSame($newTitle, $topic->fresh()->topic_title);
    }

    public function testUpdateTitleBlank(): void
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->withPost()->create(['topic_poster' => $user]);
        $title = $topic->topic_title;

        $this
            ->actingAsVerified($user)
            ->put(route('forum.topics.update', $topic), [
                'forum_topic' => [
                    'topic_title' => null,
                ],
            ])
            ->assertStatus(422);

        $this->assertSame($title, $topic->fresh()->topic_title);
    }
}
