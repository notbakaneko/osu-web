<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'header' => [
        'small' => '享受游戏以外的竞赛体验。',
        'large' => '社区评选',
    ],

    'index' => [
        'nav_title' => '列表',
    ],

    'judge' => [
        'comments' => '评论',
        'hide_judged' => '隐藏已打分的项目',
        'nav_title' => '打分',
        'no_current_vote' => '你尚未投票。',
        'update' => '更新',
        'validation' => [
            'missing_score' => '缺失分数',
            'contest_vote_judged' => '不能在已打分的竞赛中投票。',
        ],
        'voted' => '你已经提交了该项目的投票。',
    ],

    'judge_results' => [
        '_' => '打分结果',
        'creator' => '谱师',
        'score' => '分数',
        'score_std' => '',
        'total_score' => '总分',
        'total_score_std' => '',
    ],

    'voting' => [
        'judge_link' => '你是此竞赛的评委。请在这里打分！',
        'judged_notice' => '此竞赛使用了打分系统。评委正在打分。',
        'login_required' => '请登录后再投票.',
        'over' => '这场评选的投票已经结束',
        'show_voted_only' => '仅显示已投票的',

        'best_of' => [
            'none_played' => "没有符合此次评选条件的谱面！",
        ],

        'button' => [
            'add' => '投票',
            'remove' => '取消投票',
            'used_up' => '你已经用光了投票次数',
        ],

        'progress' => [
            '_' => ':used / :max 票已用',
        ],

        'requirement' => [
            'playlist_beatmapsets' => [
                'incomplete_play' => '必须完成指定歌单中的所有谱面后才能投票',
            ],
        ],
    ],

    'entry' => [
        '_' => '列表',
        'login_required' => '请登录后再参加评选。',
        'silenced_or_restricted' => '账户受限或禁言时无法参加评选。',
        'preparation' => '我们正在准备这场评选，请耐心等待！',
        'drop_here' => '将您的参赛文件拖到此处',
        'download' => '下载 .osz 文件',

        'wrong_type' => [
            'art' => '只接受 .jpg 和 .png 格式的文件.',
            'beatmap' => '只接受 .osu 格式的文件.',
            'music' => '只接受 .mp3 格式的文件.',
        ],

        'wrong_dimensions' => '参与竞赛的数量必须达到 :widthx:height',
        'too_big' => '参赛文件的大小不能超过 :limit.',
    ],

    'beatmaps' => [
        'download' => '下载模板',
    ],

    'vote' => [
        'list' => '票数',
        'count' => ':count_delimited 票',
        'points' => ':count_delimited 分',
        'points_float' => '',
    ],

    'dates' => [
        'ended' => ':date 结束',
        'ended_no_date' => '已结束',

        'starts' => [
            '_' => ':date 开始',
            'soon' => '敬请期待™
',
        ],
    ],

    'states' => [
        'entry' => '可参加',
        'voting' => '投票中',
        'results' => '已结束',
    ],
];
