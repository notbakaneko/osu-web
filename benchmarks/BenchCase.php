<?php

namespace Benchmarks;

/**
 * @Revs(1000)
 * @Iterations(50)
 * @OutputTimeUnit("seconds")
 * @OutputMode("throughput")
 * @BeforeMethods({"init"})
 */
abstract class BenchCase
{
    protected $app;

    public $subject;

    public $keys = [
        "user_id",
        "user_type",
        "group_id",
        "user_permissions",
        "user_perm_from",
        "user_ip",
        "user_regdate",
        "username",
        "username_clean",
        "user_password",
        "user_passchg",
        "user_email",
        "user_birthday",
        "user_lastvisit",
        "user_lastmark",
        "user_lastpost_time",
        "user_lastpage",
        "user_last_confirm_key",
        "user_last_search",
        "user_warnings",
        "user_last_warning",
        "user_login_attempts",
        "user_inactive_reason",
        "user_inactive_time",
        "user_posts",
        "user_lang",
        "user_timezone",
        "user_dst",
        "user_dateformat",
        "user_style",
        "user_rank",
        "user_colour",
        "user_new_privmsg",
        "user_unread_privmsg",
        "user_last_privmsg",
        "user_message_rules",
        "user_full_folder",
        "user_emailtime",
        "user_topic_show_days",
        "user_topic_sortby_type",
        "user_topic_sortby_dir",
        "user_post_show_days",
        "user_post_sortby_type",
        "user_post_sortby_dir",
        "user_notify",
        "user_notify_pm",
        "user_notify_type",
        "user_allow_pm",
        "user_allow_viewonline",
        "user_allow_viewemail",
        "user_allow_massemail",
        "user_options",
        "user_avatar",
        "user_avatar_type",
        "user_avatar_width",
        "user_avatar_height",
        "user_sig",
        "user_sig_bbcode_uid",
        "user_sig_bbcode_bitfield",
        "user_from",
        "user_lastfm",
        "user_lastfm_session",
        "user_twitter",
        "user_msnm",
        "user_jabber",
        "user_website",
        "user_occ",
        "user_interests",
        "user_actkey",
        "user_newpasswd",
        "osu_mapperrank",
        "osu_testversion",
        "osu_subscriber",
        "osu_subscriptionexpiry",
        "osu_kudosavailable",
        "osu_kudosdenied",
        "osu_kudostotal",
        "country_acronym",
        "userpage_post_id",
        "username_previous",
        "osu_featurevotes",
        "osu_playstyle",
        "osu_playmode",
        "remember_token",
    ];

    public function init()
    {
        if (!$this->app) {
            $this->app = $this->createApplication();
        }

        $this->subject = $this->getSubject();
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    public abstract function getSubject();
}
