<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DbCreate::class,
        Commands\DbSetup::class,

        Commands\EsCreateSearchBlacklist::class,
        Commands\EsIndexDocuments::class,
        Commands\EsIndexScoresQueue::class,
        Commands\EsIndexScoresSetSchema::class,
        Commands\EsIndexWiki::class,

        Commands\Ip2AsnUpdate::class,

        // modding stuff
        Commands\ModdingRankCommand::class,

        Commands\UserForumStatSyncCommand::class,
        Commands\BeatmapsMigrateMappers::class,
        Commands\BeatmapsetsHypeSyncCommand::class,
        Commands\BeatmapsetNominationSyncCommand::class,

        Commands\StoreCleanupStaleOrders::class,
        Commands\StoreExpireProducts::class,

        // builds
        Commands\BuildsCreate::class,
        Commands\BuildsUpdatePropagationHistory::class,

        // forum
        Commands\ForumTopicCoversCleanup::class,

        // leaderboard recalculation
        Commands\RankingsRecalculateCountryStats::class,

        // moddingv2 kudosu recalculation
        Commands\KudosuRecalculateDiscussionsGrants::class,

        // fix username change fail :D
        Commands\FixUsernameChangeTopicCache::class,

        // fix userchannel deletion fail
        Commands\FixMissingUserChannels::class,

        // fix forum display order
        Commands\FixForumDisplayOrder::class,

        Commands\MigrateFreshAllCommand::class,
        Commands\MigrateFreshOrRunCommand::class,

        Commands\NotificationsSendMail::class,

        Commands\OAuthDeleteExpiredTokens::class,

        Commands\RouteConvert::class,

        Commands\UserBestScoresCheckCommand::class,
        Commands\UserRecalculateRankCounts::class,

        Commands\UserNotificationsCleanup::class,
        Commands\NotificationsCleanup::class,

        Commands\ChatExpireAck::class,
        Commands\ChatChannelSetLastMessageId::class,

        Commands\BeatmapLeadersRefresh::class,

        Commands\DailyChallengeCreateNext::class,
        Commands\DailyChallengeUserStatsCalculate::class,
        Commands\DailyChallengeUserStatsRecalculate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('store:cleanup-stale-orders')
            ->daily()
            ->onOneServer();

        $schedule->command('store:expire-products')
            ->hourly()
            ->onOneServer();

        $schedule->command('builds:update-propagation-history')
            ->everyThirtyMinutes()
            ->onOneServer();

        $schedule->command('forum:topic-cover-cleanup --no-interaction')
            ->daily()
            ->onOneServer();

        $schedule->command('rankings:recalculate-country-stats')
            ->cron('25 0,3,6,9,12,15,18,21 * * *')
            ->onOneServer();

        $schedule->command('modding:rank')
            ->cron('*/20 * * * *')
            ->withoutOverlapping(120)
            ->onOneServer();

        $schedule->command('oauth:delete-expired-tokens')
            ->cron('14 1 * * *')
            ->onOneServer();

        $schedule->command('notifications:send-mail')
            ->hourly()
            ->withoutOverlapping(120)
            ->onOneServer();

        $schedule->command('user-notifications:cleanup')
            ->everyThirtyMinutes()
            ->withoutOverlapping(120)
            ->onOneServer();

        $schedule->command('notifications:cleanup')
            ->cron('15,45 * * * *')
            ->withoutOverlapping(120)
            ->onOneServer();

        $schedule->command('chat:expire-ack')
            ->everyFiveMinutes()
            ->withoutOverlapping(30)
            ->onOneServer();

        $schedule->command('daily-challenge:create-next')
            ->cron('5 0 * * *')
            ->onOneServer();

        $schedule->command('daily-challenge:user-stats-calculate')
            ->cron('10 0 * * *')
            ->onOneServer();
    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
