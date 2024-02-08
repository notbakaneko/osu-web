<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace Tests;

use App\Events\NewPrivateNotificationEvent;
use App\Http\Middleware\AuthApi;
use App\Jobs\Notifications\BroadcastNotificationBase;
use App\Libraries\OAuth\EncodeToken;
use App\Libraries\Search\ScoreSearch;
use App\Libraries\Session\Store as SessionStore;
use App\Models\Beatmapset;
use App\Models\Build;
use App\Models\OAuth\Client;
use App\Models\User;
use Artisan;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Testing\Fakes\MailFake;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Queue;
use ReflectionMethod;
use ReflectionProperty;

class TestCase extends BaseTestCase
{
    use ArraySubsetAsserts, CreatesApplication, DatabaseTransactions;

    public static function withDbAccess(callable $callback): void
    {
        $db = static::createApp()->make('db');

        $callback();

        static::resetAppDb($db);
    }

    protected static function createClientToken(Build $build, ?int $clientTime = null): string
    {
        $data = strtoupper(bin2hex($build->hash).bin2hex(pack('V', $clientTime ?? time())));
        $expected = hash_hmac('sha1', $data, '');

        return strtoupper(bin2hex(random_bytes(40)).$data.$expected.'00');
    }

    protected static function fileList($path, $suffix)
    {
        return array_map(
            fn ($file) => [basename($file, $suffix), $path],
            glob("{$path}/*{$suffix}"),
        );
    }

    protected static function reindexScores()
    {
        $search = new ScoreSearch();
        $search->deleteAll();
        $search->refresh();
        Artisan::call('es:index-scores:queue', [
            '--all' => true,
            '--no-interaction' => true,
        ]);
        $search->indexWait();
    }

    protected static function resetAppDb(DatabaseManager $database): void
    {
        foreach (array_keys($GLOBALS['cfg']['database']['connections']) as $name) {
            $connection = $database->connection($name);

            $connection->rollBack();
            $connection->disconnect();
        }
    }

    protected $connectionsToTransact = [
        'mysql',
        'mysql-chat',
        'mysql-mp',
        'mysql-store',
        'mysql-updates',
    ];

    protected array $expectedCountsCallbacks = [];

    public static function regularOAuthScopesDataProvider()
    {
        $data = [];

        foreach (Passport::scopes()->pluck('id') as $scope) {
            // just skip over any scopes that require special conditions for now.
            if (in_array($scope, ['chat.read', 'chat.write', 'chat.write_manage', 'delegate'], true)) {
                continue;
            }

            $data[] = [$scope];
        }

        return $data;
    }

    protected function setUp(): void
    {
        $this->beforeApplicationDestroyed(fn () => $this->runExpectedCountsCallbacks());

        parent::setUp();

        // change config setting because we need more than 1 for the tests.
        config_set('osu.oauth.max_user_clients', 100);

        // Force connections to reset even if transactional tests were not used.
        // Should fix tests going wonky when different queue drivers are used, or anything that
        // breaks assumptions of object destructor timing.
        $db = $this->app->make('db');
        $this->beforeApplicationDestroyed(fn () => static::resetAppDb($db));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->expectedCountsCallbacks = [];
    }

    /**
     * Act as a User with OAuth scope permissions.
     */
    protected function actAsScopedUser(?User $user, ?array $scopes = ['*'], ?Client $client = null): void
    {
        $this->actingWithToken($this->createToken(
            $user,
            $scopes,
            $client ?? Client::factory()->create(),
        ));
    }

    protected function actAsUser(?User $user, bool $verified = false, $driver = null)
    {
        if ($user === null) {
            return;
        }

        $this->be($user, $driver);

        $this->withSession(['verified' => $verified]);
    }

    /**
     * This is for tests that will skip the request middleware stack.
     *
     * @param Token $token OAuth token.
     * @param string $driver Auth driver to use.
     * @return void
     */
    protected function actAsUserWithToken(Token $token, $driver = null)
    {
        $guard = app('auth')->guard($driver);
        $user = $token->getResourceOwner();

        if ($user !== null) {
            // guard doesn't accept null user.
            $guard->setUser($user);
            $user->withAccessToken($token);
        }

        // This is for test that do not make actual requests;
        // tests that make requests will override this value with a new one
        // and the token gets resolved in middleware.
        request()->attributes->set(AuthApi::REQUEST_OAUTH_TOKEN_KEY, $token);

        app('auth')->shouldUse($driver);
    }

    protected function actingAsVerified($user)
    {
        $this->actAsUser($user, true);

        return $this;
    }

    protected function actingWithToken($token)
    {
        $this->actAsUserWithToken($token);

        $encodedToken = EncodeToken::encodeAccessToken($token);

        return $this->withHeaders([
            'Authorization' => "Bearer {$encodedToken}",
        ]);
    }

    protected function createAllowedScopesDataProvider(array $allowedScopes)
    {
        $data = Passport::scopes()->pluck('id')->map(function ($scope) use ($allowedScopes) {
            return [[$scope], in_array($scope, $allowedScopes, true)];
        })->all();

        // scopeless tokens should fail in general.
        $data[] = [[], false];

        return $data;
    }

    protected function createVerifiedSession($user): SessionStore
    {
        $ret = SessionStore::findOrNew();
        $ret->put(\Auth::getName(), $user->getKey());
        $ret->put('verified', true);
        $ret->migrate(false);
        $ret->save();

        return $ret;
    }

    protected function clearMailFake()
    {
        $mailer = app('mailer');
        if ($mailer instanceof MailFake) {
            $this->invokeSetProperty($mailer, 'mailables', []);
            $this->invokeSetProperty($mailer, 'queuedMailables', []);
        }
    }

    /**
     * Creates an OAuth token for the specified authorizing user.
     *
     * @param User|null $user The user that authorized the token.
     * @param array|null $scopes scopes granted
     * @param Client|null $client The client the token belongs to.
     * @return Token
     */
    protected function createToken(?User $user, ?array $scopes = null, ?Client $client = null)
    {
        return ($client ?? Client::factory()->create())->tokens()->create([
            'expires_at' => now()->addDays(1),
            'id' => uniqid(),
            'revoked' => false,
            'scopes' => $scopes,
            'user_id' => $user?->getKey(),
            'verified' => true,
        ]);
    }

    protected function expectCountChange(callable $callback, int $change, string $message = '')
    {
        $this->expectedCountsCallbacks[] = [
            'callback' => $callback,
            'expected' => $callback() + $change,
            'message' => $message,
        ];
    }

    protected function expectExceptionCallable(callable $callable, ?string $exceptionClass, ?string $exceptionMessage = null)
    {
        try {
            $callable();
        } catch (\Throwable $e) {
            $this->assertSame($exceptionClass, $e::class);

            if ($exceptionMessage !== null) {
                $this->assertSame($exceptionMessage, $e->getMessage());
            }

            return;
        }

        // trigger fail if expecting exception but doesn't fail.
        if ($exceptionClass !== null) {
            static::fail("Did not throw expected {$exceptionClass}");
        }
    }

    protected function inReceivers(Model $model, NewPrivateNotificationEvent|BroadcastNotificationBase $obj): bool
    {
        return in_array($model->getKey(), $obj->getReceiverIds(), true);
    }

    protected function invokeMethod($obj, string $name, array $params = [])
    {
        $method = new ReflectionMethod($obj, $name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $params);
    }

    protected function invokeProperty($obj, string $name)
    {
        $property = new ReflectionProperty($obj, $name);
        $property->setAccessible(true);

        return $property->getValue($obj);
    }

    protected function invokeSetProperty($obj, string $name, $value)
    {
        $property = new ReflectionProperty($obj, $name);
        $property->setAccessible(true);

        $property->setValue($obj, $value);
    }

    protected function makeBeatmapsetDiscussionPostParams(Beatmapset $beatmapset, string $messageType)
    {
        return [
            'beatmapset_id' => $beatmapset->getKey(),
            'beatmap_discussion' => [
                'message_type' => $messageType,
            ],
            'beatmap_discussion_post' => [
                'message' => 'Hello',
            ],
        ];
    }

    protected function normalizeHTML($html)
    {
        return str_replace('<br />', "<br />\n", str_replace("\n", '', preg_replace('/>\s*</s', '><', trim($html))));
    }

    protected function runFakeQueue()
    {
        collect(Queue::pushedJobs())->flatten(1)->each(function ($job) {
            $job['job']->handle();
        });

        // clear queue jobs after running
        // FIXME: this won't work if a job queues another job and you want to run that job.
        $this->invokeSetProperty(app('queue'), 'jobs', []);
    }

    protected function withInterOpHeader($url)
    {
        return $this->withHeaders([
            'X-LIO-Signature' => hash_hmac('sha1', $url, $GLOBALS['cfg']['osu']['legacy']['shared_interop_secret']),
        ]);
    }

    protected function withPersistentSession(SessionStore $session): static
    {
        $session->save();

        return $this->withCookies([
            $session->getName() => $session->getId(),
        ]);
    }

    private function runExpectedCountsCallbacks()
    {
        foreach ($this->expectedCountsCallbacks as $expectedCount) {
            $after = $expectedCount['callback']();
            $this->assertSame($expectedCount['expected'], $after, $expectedCount['message']);
        }
    }
}
