<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'all_read' => '已閱畢所有通知！',
    'delete' => '刪除 :type',
    'loading' => '正在載入未讀通知...',
    'mark_read' => '清除 :type',
    'none' => '沒有通知',
    'see_all' => '查看所有通知',
    'see_channel' => '前往聊天',
    'verifying' => '請驗證此工作階段以查看通知',

    'action_type' => [
        '_' => '全部',
        'beatmapset' => '圖譜',
        'build' => '版本',
        'channel' => '聊天',
        'forum_topic' => '論壇',
        'news_post' => '新聞',
        'team' => '團隊',
        'user' => '個人資料',
    ],

    'filters' => [
        '_' => '全部',
        'beatmapset' => '圖譜',
        'build' => '版本',
        'channel' => '聊天',
        'forum_topic' => '討論區',
        'news_post' => '最新消息',
        'team' => '團隊',
        'user' => '個人檔案',
    ],

    'item' => [
        'beatmapset' => [
            '_' => '圖譜',

            'beatmap_owner_change' => [
                '_' => '客串難度',
                'beatmap_owner_change' => '您現在是圖譜 ":title" 中難度 ":beatmap" 的作者',
                'beatmap_owner_change_compact' => '您現在是難度 ":beatmap" 的作者',
            ],

            'beatmapset_discussion' => [
                '_' => '圖譜討論',
                'beatmapset_discussion_lock' => '已鎖定「:title」的討論',
                'beatmapset_discussion_lock_compact' => '討論已被鎖定',
                'beatmapset_discussion_post_new' => ':username 在「:title」中發表了新的貼文：":content"',
                'beatmapset_discussion_post_new_empty' => ':username發表了主題為:title的新貼文',
                'beatmapset_discussion_post_new_compact' => ':username 的新主題: 「:content」',
                'beatmapset_discussion_post_new_compact_empty' => ':username 的新主題',
                'beatmapset_discussion_review_new' => ':username 在「:title」中發表了新的審核，包含:review_counts',
                'beatmapset_discussion_review_new_compact' => ':username 發表了新的審核，包含:review_counts',
                'beatmapset_discussion_unlock' => '討論於 ":title" 已解鎖',
                'beatmapset_discussion_unlock_compact' => '討論已被解鎖',

                'review_count' => [
                    'praises' => ':count_delimited 個讚',
                    'problems' => ':count_delimited 個問題',
                    'suggestions' => ':count_delimited 個建議',
                ],
            ],

            'beatmapset_problem' => [
                '_' => '合格圖譜問題',
                'beatmapset_discussion_qualified_problem' => ':username 在「:title」中回報問題：「:content」',
                'beatmapset_discussion_qualified_problem_empty' => ':username 在「:title」中回報問題',
                'beatmapset_discussion_qualified_problem_compact' => ':username 回報問題「:content」',
                'beatmapset_discussion_qualified_problem_compact_empty' => '由 :username 回報',
            ],

            'beatmapset_state' => [
                '_' => '圖譜狀態已變更',
                'beatmapset_disqualify' => '「:title」被取消資格',
                'beatmapset_disqualify_compact' => '圖譜被取消資格',
                'beatmapset_love' => '「:title」被晉升為社群喜愛',
                'beatmapset_love_compact' => '圖譜晉升為社群喜愛',
                'beatmapset_nominate' => '「:title」已被提名',
                'beatmapset_nominate_compact' => '圖譜已被提名',
                'beatmapset_qualify' => '「:title」已獲得足夠的提名，因此進入了上架隊列',
                'beatmapset_qualify_compact' => '圖譜已進入上架列隊',
                'beatmapset_rank' => '「:title」已進榜',
                'beatmapset_rank_compact' => '圖譜已進榜',
                'beatmapset_remove_from_loved' => '「:title」已從社群喜愛中移除',
                'beatmapset_remove_from_loved_compact' => '圖譜已從社群喜愛中移除',
                'beatmapset_reset_nominations' => '「:title」的提名已被重設',
                'beatmapset_reset_nominations_compact' => '提名已被重設',
            ],

            'comment' => [
                '_' => '新留言',

                'comment_new' => ':username 在「:title」中評論了 「:content」',
                'comment_new_compact' => ':username 評論了 「:content」',
                'comment_reply' => ':username 在「:title」中回覆了「:content」',
                'comment_reply_compact' => ':username 回覆了「:content」',
            ],
        ],

        'channel' => [
            '_' => '聊天',

            'announcement' => [
                '_' => '新增公告',

                'announce' => [
                    'channel_announcement' => ':username 發表了「:title」',
                    'channel_announcement_compact' => ':title',
                    'channel_announcement_group' => ':username 發表的公告',
                ],
            ],

            'channel' => [
                '_' => '新訊息',

                'pm' => [
                    'channel_message' => ':username 發表了「:title」',
                    'channel_message_compact' => ':title',
                    'channel_message_group' => '來自 :username',
                ],
            ],

            'channel_team' => [
                '_' => '新的團隊訊息',

                'team' => [
                    'channel_team' => ':username 説 ":title"',
                    'channel_team_compact' => ':username 説 ":title"',
                    'channel_team_group' => ':username 説 ":title"',
                ],
            ],
        ],

        'build' => [
            '_' => '更新日誌',

            'comment' => [
                '_' => '新留言',

                'comment_new' => ':username 在「:title」中評論了 「:content」',
                'comment_new_compact' => ':username 評論了 「:content」',
                'comment_reply' => ':username 在「:title」中回覆了「:content」',
                'comment_reply_compact' => ':username 回覆了「:content」',
            ],
        ],

        'news_post' => [
            '_' => '最新消息',

            'comment' => [
                '_' => '新留言',

                'comment_new' => ':username 在「:title」中評論了 「:content」',
                'comment_new_compact' => ':username 評論了 「:content」',
                'comment_reply' => ':username 在「:title」中回覆了「:content」',
                'comment_reply_compact' => ':username 回覆了「:content」',
            ],
        ],

        'forum_topic' => [
            '_' => '論壇主題',

            'forum_topic_reply' => [
                '_' => '新論壇回覆',
                'forum_topic_reply' => ':username 回覆了主題 「:title」',
                'forum_topic_reply_compact' => ':username 回覆了',
            ],
        ],

        'team' => [
            'team_application' => [
                '_' => '團隊加入請求',

                'team_application_accept' => "你現在是 :title 團隊的成員了",
                'team_application_accept_compact' => "你現在是 :title 團隊的成員了",

                'team_application_group' => '隊伍邀請紀錄',

                'team_application_reject' => '你加入 :title 團隊的請求已被拒絕',
                'team_application_reject_compact' => '你加入 :title 團隊的請求已被拒絕',
                'team_application_store' => ':title 想要加入你的團隊',
                'team_application_store_compact' => ':title 想要加入你的團隊',
            ],
        ],

        'user' => [
            'user_beatmapset_new' => [
                '_' => '新圖譜',

                'user_beatmapset_new' => ':username 上傳了標題為 “:title” 的新圖譜',
                'user_beatmapset_new_compact' => '新圖譜 ":title"',
                'user_beatmapset_new_group' => ':username 上傳了新圖譜',

                'user_beatmapset_revive' => '「:title」圖譜已被 :username 恢復',
                'user_beatmapset_revive_compact' => '已恢復「:title」圖譜',
            ],
        ],

        'user_achievement' => [
            '_' => '成就',

            'user_achievement_unlock' => [
                '_' => '新成就',
                'user_achievement_unlock' => '解鎖「:title」！',
                'user_achievement_unlock_compact' => '解鎖「:title」！',
                'user_achievement_unlock_group' => '勳章解鎖！',
            ],
        ],
    ],

    'mail' => [
        'beatmapset' => [
            'beatmap_owner_change' => [
                'beatmap_owner_change' => '您現在是圖譜 ":title" 的客串譜師',
            ],

            'beatmapset_discussion' => [
                'beatmapset_discussion_lock' => '「:title」的討論已被鎖定',
                'beatmapset_discussion_post_new' => '「:title」的討論有新的更新',
                'beatmapset_discussion_unlock' => '「:title」的討論已解除鎖定',
            ],

            'beatmapset_problem' => [
                'beatmapset_discussion_qualified_problem' => '「:title」中被回報了一個新問題',
            ],

            'beatmapset_state' => [
                'beatmapset_disqualify' => '「:title」已被取消資格',
                'beatmapset_love' => '「:title」被提升為社群喜愛',
                'beatmapset_nominate' => '":title" 已被提名',
                'beatmapset_qualify' => '「:title」已獲得足夠的提名，因此進入了上架隊列',
                'beatmapset_rank' => '「:title」已進榜',
                'beatmapset_remove_from_loved' => '「:title」已從社群喜愛中移除',
                'beatmapset_reset_nominations' => '「:title」的提名已被重設',
            ],

            'comment' => [
                'comment_new' => '圖譜【:title】中有新的留言',
            ],
        ],

        'channel' => [
            'announcement' => [
                'channel_announcement' => '「:name」裡有新的公告',
            ],
            'channel' => [
                'channel_message' => '你已收到來自 :username 的訊息',
            ],
            'channel_team' => [
                'channel_team' => '有一個來自隊伍 「:name」的訊息',
            ],
        ],

        'build' => [
            'comment' => [
                'comment_new' => '更新日誌【:title】中有新的留言',
            ],
        ],

        'news_post' => [
            'comment' => [
                'comment_new' => '新聞【:title】中有新的留言',
            ],
        ],

        'forum_topic' => [
            'forum_topic_reply' => [
                'forum_topic_reply' => '":title" 中有新的回覆',
            ],
        ],

        'team' => [
            'team_application' => [
                'team_application_accept' => "你現在是 :title 團隊的成員了",
                'team_application_reject' => '你加入 :title 團隊的請求已被拒絕',
                'team_application_store' => ':title 請求加入你的團隊',
            ],
        ],

        'user' => [
            'user_beatmapset_new' => [
                'user_beatmapset_new' => ':username 建立了新圖譜',
                'user_beatmapset_revive' => ':username 復原了圖譜',
            ],
        ],
    ],
];
