<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'deleted' => '[已删除的用户]',

    'beatmapset_activities' => [
        'title' => ":user 的摸图历史",
        'title_compact' => '摸图',

        'discussions' => [
            'title_recent' => '最近打开的讨论',
        ],

        'events' => [
            'title_recent' => '最近事件',
        ],

        'posts' => [
            'title_recent' => '最近帖子',
        ],

        'votes_received' => [
            'title_most' => '最多获赞（最近三个月）',
        ],

        'votes_made' => [
            'title_most' => '最多点赞（最近三个月）',
        ],
    ],

    'blocks' => [
        'banner_text' => '你已经屏蔽此用户。',
        'comment_text' => '此评论已隐藏。',
        'blocked_count' => '被屏蔽的用户 (:count)',
        'hide_profile' => '隐藏用户资料',
        'hide_comment' => '隐藏',
        'forum_post_text' => '此帖子已被隐藏。',
        'not_blocked' => '此用户未被屏蔽。',
        'show_profile' => '显示用户资料',
        'show_comment' => '展开',
        'too_many' => '屏蔽用户数量达到最大限制。',
        'button' => [
            'block' => '屏蔽',
            'unblock' => '解除屏蔽',
        ],
    ],

    'card' => [
        'gift_supporter' => '赠送支持者标签',
        'loading' => '加载中...',
        'send_message' => '发送消息',
    ],

    'create' => [
        'form' => [
            'password' => '密码',
            'password_confirmation' => '确认密码',
            'submit' => '注册新账户',
            'user_email' => '邮箱地址',
            'user_email_confirmation' => '确认邮箱地址',
            'username' => '用户名',

            'tos_notice' => [
                '_' => '完成注册，即视为您同意了 :link',
                'link' => '服务协议',
            ],
        ],
    ],

    'disabled' => [
        'title' => '哎呀！看起来您的账户已被禁用。',
        'warning' => "如果您违反了规则，原则上在一个月的期限以内我们不会考虑解禁您的账户。在此之后，您如果需要，可以随时联系我们。请注意，在一个账户被封后创建新账户会<strong>使您的封禁期限被延长</strong>。您更需要注意<strong>您每创建一个新账户都会更严重地违反规则</strong>。我们强烈建议您不要误入歧途。",

        'if_mistake' => [
            '_' => '如果你觉得我们误封了你的账号，你可以通过电子邮件（:email）或者点击本页面右下方的问号来联系我们。一般来说，我们犯错的几率是很低的。我们只以可靠的数据为参考进行这一类的操作。如果你执意违反规则，我们保留拒绝你的请求的权利。',
            'email' => '电子邮件',
        ],

        'reasons' => [
            'compromised' => '我们认为你的账户已被盗用。在确认身份期间，账户被暂时停用。',
            'opening' => '您的账户可能由于这几种原因被禁用：',

            'tos' => [
                '_' => '您已经违反了一条或多条 :community_rules 或是 :tos。',
                'community_rules' => '社区规则',
                'tos' => '服务条款',
            ],
        ],
    ],

    'filtering' => [
        'by_game_mode' => '成员按游戏模式筛选',
    ],

    'force_reactivation' => [
        'reason' => [
            'inactive' => "您已经很长时间没有使用您的账户了。",
            'inactive_different_country' => "您已经很长时间没有使用您的账户了。",
        ],
    ],

    'login' => [
        '_' => '登录',
        'button' => '登录',
        'button_posting' => '登录中...',
        'email_login_disabled' => '当前不可以使用电子邮件登录。请使用您的用户名。',
        'failed' => '登录失败',
        'forgot' => '忘记密码？',
        'info' => '请登录以继续',
        'invalid_captcha' => '验证码无效，请刷新页面后重试。',
        'locked_ip' => 'IP 已被锁定，请稍等几分钟。',
        'password' => '密码',
        'register' => "没有 osu! 账户？现在就注册一个！",
        'remember' => '在这台电脑上记住我',
        'title' => '登录以继续',
        'username' => '用户名',

        'beta' => [
            'main' => 'Beta 仅限于特定用户访问',
            'small' => '（在不久后将对 osu! 支持者开放）',
        ],
    ],

    'ogp' => [
        'modding_description' => '谱面：:counts',
        'modding_description_empty' => '玩家没有任何谱面...',

        'description' => [
            '_' => '排名 (:ruleset): :global | :country',
            'country' => '地区 :rank',
            'global' => '全球 :rank',
        ],
    ],

    'posts' => [
        'title' => ':username 的帖子',
    ],

    'anonymous' => [
        'login_link' => '点击登录',
        'login_text' => '登录',
        'username' => '游客',
        'error' => '请先登录',
    ],
    'logout_confirm' => '确定要退出吗？o(TヘTo)',
    'report' => [
        'button_text' => '举报',
        'comments' => '附加信息',
        'placeholder' => '请提供你认为可能有用的所有信息。',
        'reason' => '原因',
        'thanks' => '感谢你的报告！',
        'title' => '举报 :username ？',

        'actions' => [
            'send' => '发送报告',
            'cancel' => '取消',
        ],

        'options' => [
            'cheating' => '作弊',
            'inappropriate_chat' => '',
            'insults' => '侮辱 我/其他人',
            'multiple_accounts' => '使用多账号（开小号）',
            'nonsense' => '无意义内容',
            'other' => '其他（在下方输入原因）',
            'spam' => '刷屏/垃圾广告',
            'unwanted_content' => '发布包含不当内容的链接',
        ],
    ],
    'restricted_banner' => [
        'title' => '账户进入限制模式！',
        'message' => '处于被限制状态时，你将不能与其他玩家互动，成绩只有你自己可见。限制通常是系统自动给予，通常会在 24 小时内解除。:link',
        'message_link' => '点击此页了解更多。',
    ],
    'show' => [
        'age' => ':age 岁',
        'change_avatar' => '更换头像！',
        'first_members' => '元老玩家',
        'is_developer' => 'osu! 开发者',
        'is_supporter' => 'osu! 支持者',
        'joined_at' => '注册于 :date',
        'lastvisit' => '最后活跃：:date',
        'lastvisit_online' => '当前在线',
        'missingtext' => '你可能打错字了！（或者该用户已经被封禁）',
        'origin_country' => '来自 :country',
        'previous_usernames' => '曾用名',
        'plays_with' => '用 :devices 游玩',

        'comments_count' => [
            '_' => '发表了 :link',
            'count' => ':count_delimited 条评论',
        ],
        'cover' => [
            'to_0' => '隐藏封面',
            'to_1' => '显示封面',
        ],
        'daily_challenge' => [
            'daily' => '每日连续完成数',
            'daily_streak_best' => '最佳连续完成天数',
            'daily_streak_current' => '当前连续完成天数',
            'playcount' => '总参加天数',
            'title' => '每日\n挑战',
            'top_10p_placements' => '排名达到前 10% 次数',
            'top_50p_placements' => '排名达到前 50% 次数',
            'weekly' => '每周完成数',
            'weekly_streak_best' => '最佳连续完成周数',
            'weekly_streak_current' => '当前连续完成周数',

            'unit' => [
                'day' => ':value 天',
                'week' => ':value 周',
            ],
        ],
        'edit' => [
            'cover' => [
                'button' => '更换个人资料封面',
                'defaults_info' => '在将来会有更多封面可用',
                'holdover_remove_confirm' => "之前选择的个人资料封面已不再可用。在更换封面后将无法再次选择。继续吗？",
                'title' => '封面',

                'upload' => [
                    'broken_file' => '上传失败。请检查上传的图片然后重试。',
                    'button' => '上传图片',
                    'dropzone' => '拖拽到此处',
                    'dropzone_info' => '将图片拖动到这里也可以上传',
                    'size_info' => '图片尺寸最好是 2400x620 像素',
                    'too_large' => '上传的图片过大。',
                    'unsupported_format' => '不支持的格式。',

                    'restriction_info' => [
                        '_' => '自定义头图只有 :link 可用',
                        'link' => 'osu! 支持者',
                    ],
                ],
            ],

            'default_playmode' => [
                'is_default_tooltip' => '默认游戏模式',
                'set' => '设置 :mode 为个人资料的默认游戏模式',
            ],

            'hue' => [
                'reset_no_supporter' => '确定重置颜色为默认吗？如果之后想要修改成其他颜色，需要您拥有支持者标签。',
                'title' => '颜色',

                'supporter' => [
                    '_' => '自定义色彩主题只有 :link 可用',
                    'link' => 'osu! 支持者',
                ],
            ],
        ],

        'extra' => [
            'none' => '无',
            'unranked' => '最近没玩过',

            'achievements' => [
                'achieved-on' => ':date 达成',
                'locked' => '锁定',
                'title' => '成就',
            ],
            'beatmaps' => [
                'by_artist' => 'by :artist',
                'title' => '谱面',

                'favourite' => [
                    'title' => '收藏的谱面',
                ],
                'graveyard' => [
                    'title' => '已停更的谱面',
                ],
                'guest' => [
                    'title' => '客串制作的谱面',
                ],
                'loved' => [
                    'title' => '社区喜爱 (Loved) 谱面',
                ],
                'nominated' => [
                    'title' => '提名并上架 (Ranked) 的谱面',
                ],
                'pending' => [
                    'title' => '待定 (Pending) 谱面',
                ],
                'ranked' => [
                    'title' => '上架 (Ranked) 谱面',
                ],
            ],
            'discussions' => [
                'title' => '讨论',
                'title_longer' => '最近讨论',
                'show_more' => '查看更多讨论',
            ],
            'events' => [
                'title' => '事件',
                'title_longer' => '最近事件',
                'show_more' => '查看更多事件',
            ],
            'historical' => [
                'title' => '历史记录',

                'monthly_playcounts' => [
                    'title' => '游玩记录',
                    'count_label' => '游玩次数',
                ],
                'most_played' => [
                    'count' => '游玩次数',
                    'title' => '玩得最多的谱面',
                ],
                'recent_plays' => [
                    'accuracy' => '准确率：:percentage',
                    'title' => '最近24小时游玩',
                ],
                'replays_watched_counts' => [
                    'title' => '回放被观看记录',
                    'count_label' => '回放被观看次数',
                ],
            ],
            'kudosu' => [
                'recent_entries' => '最近 Kudosu 记录',
                'title' => 'Kudosu!',
                'total' => '总共获得 kudosu',

                'entry' => [
                    'amount' => ':amount kudosu',
                    'empty' => "该用户还没有收到过 kudosu ！",

                    'beatmap_discussion' => [
                        'allow_kudosu' => [
                            'give' => '因移除讨论帖 :post 内送出的 kudosu 而获得 :amount kudosu',
                        ],

                        'deny_kudosu' => [
                            'reset' => '在讨论帖 :post 中被移除 :amount',
                        ],

                        'delete' => [
                            'reset' => '因讨论帖 :post 被删除而失去 :amount',
                        ],

                        'restore' => [
                            'give' => '因讨论帖 :post 被恢复而获得 :amount',
                        ],

                        'vote' => [
                            'give' => '因在讨论帖 :post 中得到了足够票数而获得 :amount',
                            'reset' => '因在讨论帖 :post 中丢失了票数而失去 :amount',
                        ],

                        'recalculate' => [
                            'give' => '因讨论帖 :post 的投票重新计算而获得 :amount',
                            'reset' => '因讨论帖 :post 的投票重新计算而失去 :amount',
                        ],
                    ],

                    'forum_post' => [
                        'give' => '在帖子 :post 中被 :giver 给予 :amount ',
                        'reset' => '在帖子 :post 中被 :giver 重置 kudosu ',
                        'revoke' => '在帖子 :post 中被 :giver 移除 kudosu ',
                    ],
                ],

                'total_info' => [
                    '_' => '取决于你对制谱的贡献如何。查看 :link 获得更多信息。',
                    'link' => '此页面',
                ],
            ],
            'me' => [
                'title' => '个人介绍',
            ],
            'medals' => [
                'empty' => "该用户还没有获得成就。;_;",
                'recent' => '最近取得',
                'title' => '成就',
            ],
            'playlists' => [
                'title' => '歌单游戏',
            ],
            'posts' => [
                'title' => '回复',
                'title_longer' => '最近回复',
                'show_more' => '查看更多回复',
            ],
            'recent_activity' => [
                'title' => '最近活动',
            ],
            'realtime' => [
                'title' => '多人游戏',
            ],
            'top_ranks' => [
                'download_replay' => '下载回放',
                'not_ranked' => '只能在上架 (Ranked) 谱面中获得 pp。',
                'pp_weight' => '权重：:percentage',
                'view_details' => '查看详情',
                'title' => '成绩',

                'best' => [
                    'title' => '最好成绩',
                ],
                'first' => [
                    'title' => '第一名',
                ],
                'pin' => [
                    'to_0' => '取消置顶',
                    'to_0_done' => '已取消置顶成绩',
                    'to_1' => '置顶',
                    'to_1_done' => '已置顶成绩',
                ],
                'pinned' => [
                    'title' => '置顶成绩',
                ],
            ],
            'votes' => [
                'given' => '最近三个月的给予投票',
                'received' => '最近三个月所得投票',
                'title' => '投票',
                'title_longer' => '最近投票',
                'vote_count' => ':count_delimited 票',
            ],
            'account_standing' => [
                'title' => '账号状态',
                'bad_standing' => ":username 的账号存在不良记录 :(",
                'remaining_silence' => ':username的禁言将在 :duration 解除',

                'recent_infringements' => [
                    'title' => '最近记录',
                    'date' => '时间',
                    'action' => '处理',
                    'length' => '时长',
                    'length_indefinite' => '无限期',
                    'description' => '原因',
                    'actor' => '裁决者： :username',

                    'actions' => [
                        'restriction' => '封禁',
                        'silence' => '禁言',
                        'tournament_ban' => '禁赛',
                        'note' => '注释',
                    ],
                ],
            ],
        ],

        'info' => [
            'discord' => '',
            'interests' => '兴趣',
            'location' => '所在地',
            'occupation' => '职业',
            'twitter' => '',
            'website' => '网站',
        ],
        'not_found' => [
            'reason_1' => '他可能换了用户名。',
            'reason_2' => '该账号由于安全或滥用问题暂时不可用。',
            'reason_3' => '你可能输错用户名了！',
            'reason_header' => '可能是由于以下原因：',
            'title' => '找不到指定的用户！;_;',
        ],
        'page' => [
            'button' => '修改资料页面',
            'description' => '<strong>个人介绍</strong> 是可以自定义内容的展示区.',
            'edit_big' => '编辑',
            'placeholder' => '在这里编辑',

            'restriction_info' => [
                '_' => '你需要成为 :link 才能使用此功能。',
                'link' => 'osu! 支持者',
            ],
        ],
        'post_count' => [
            '_' => '发表了 :link',
            'count' => ':count_delimited 篇帖子|:count_delimited 篇帖子',
        ],
        'rank' => [
            'country' => ':mode 模式的地区排名',
            'country_simple' => '地区排名',
            'global' => ':mode 模式的全球排名',
            'global_simple' => '全球排名',
            'highest' => ':date 达成生涯最高排名 :rank',
        ],
        'season_stats' => [
            'division_top_percentage' => '',
            'total_score' => '',
        ],
        'stats' => [
            'hit_accuracy' => '准确率',
            'level' => '等级 :level',
            'level_progress' => '到下一级的进度',
            'maximum_combo' => '最大连击',
            'medals' => '奖章',
            'play_count' => '游戏次数',
            'play_time' => '游戏时间',
            'ranked_score' => '计分成绩总分',
            'replays_watched_by_others' => '回放被观看次数',
            'score_ranks' => '得分等级',
            'total_hits' => '总命中次数',
            'total_score' => '总分',
            // modding stats
            'graveyard_beatmapset_count' => '坟场里的谱面',
            'loved_beatmapset_count' => '社区喜爱 (Loved) 谱面',
            'pending_beatmapset_count' => '待定 (Pending) 谱面',
            'ranked_beatmapset_count' => '上架 (Ranked) 谱面',
        ],
    ],

    'silenced_banner' => [
        'title' => '你已被禁言。',
        'message' => '部分操作将不可用。',
    ],

    'status' => [
        'all' => '所有',
        'online' => '在线',
        'offline' => '离线',
    ],
    'store' => [
        'from_client' => '请使用游戏客户端注册！',
        'from_web' => '请通过网页完成注册',
        'saved' => '账户已创建',
    ],
    'verify' => [
        'title' => '账户认证',
    ],

    'view_mode' => [
        'brick' => '方格视图',
        'card' => '卡片视图',
        'list' => '列表视图',
    ],
];
