<?php

/**
 *    Copyright 2015-2017 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Models;

/**
 *
 * @property int $user_id
 * @property int $user_type
 * @property int $group_id
 * @property mixed|null $user_permissions
 * @property int|null $user_perm_from
 * @property string $user_ip
 * @property int $user_regdate
 * @property string $username
 * @property string $username_clean
 * @property string $user_password
 * @property int $user_passchg
 * @property string|null $user_email
 * @property string $user_birthday
 * @property int $user_lastvisit
 * @property int $user_lastmark
 * @property int $user_lastpost_time
 * @property string $user_lastpage
 * @property string $user_last_confirm_key
 * @property int $user_last_search
 * @property int $user_warnings
 * @property int $user_last_warning
 * @property int $user_login_attempts
 * @property int $user_inactive_reason
 * @property int $user_inactive_time
 * @property int $user_posts
 * @property string $user_lang
 * @property float $user_timezone
 * @property int $user_dst
 * @property string $user_dateformat
 * @property int $user_style
 * @property int $user_rank
 * @property string $user_colour
 * @property int $user_new_privmsg
 * @property int $user_unread_privmsg
 * @property int $user_last_privmsg
 * @property int $user_message_rules
 * @property int $user_full_folder
 * @property int $user_emailtime
 * @property int $user_topic_show_days
 * @property string $user_topic_sortby_type
 * @property string $user_topic_sortby_dir
 * @property int $user_post_show_days
 * @property string $user_post_sortby_type
 * @property string $user_post_sortby_dir
 * @property int $user_notify
 * @property int $user_notify_pm
 * @property int $user_notify_type
 * @property boolean $user_allow_pm
 * @property boolean $user_allow_viewonline
 * @property int $user_allow_viewemail
 * @property int $user_allow_massemail
 * @property int $user_options
 * @property string $user_avatar
 * @property int $user_avatar_type
 * @property int $user_avatar_width
 * @property int $user_avatar_height
 * @property mixed $user_sig
 * @property string $user_sig_bbcode_uid
 * @property string $user_sig_bbcode_bitfield
 * @property string $user_from
 * @property string $user_lastfm
 * @property string $user_lastfm_session
 * @property string $user_twitter
 * @property string $user_msnm
 * @property string $user_jabber
 * @property string $user_website
 * @property string|null $user_occ
 * @property string|null $user_interests
 * @property string $user_actkey
 * @property string $user_newpasswd
 * @property float $osu_mapperrank
 * @property int $osu_testversion
 * @property boolean $osu_subscriber
 * @property Carbon\Carbon|null $osu_subscriptionexpiry
 * @property int $osu_kudosavailable
 * @property int $osu_kudosdenied
 * @property int $osu_kudostotal
 * @property string $country_acronym
 * @property int|null $userpage_post_id
 * @property string|null $username_previous
 * @property int $osu_featurevotes
 * @property int $osu_playstyle
 * @property int $osu_playmode
 * @property string|null $remember_token
 */
class DeletedUser extends User
{
    public $user_avatar = null;
    public $username = '[deleted user]';
}
