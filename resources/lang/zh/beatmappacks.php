<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'index' => [
        'description' => '围绕某个相同主题打包好的曲包',
        'empty' => '敬请期待！',
        'nav_title' => '列表',
        'title' => '曲包',

        'blurb' => [
            'important' => '下载前必读',
            'install_instruction' => '导入：当曲包下载完成后，把它解压至 Songs 文件夹内即可。osu! 会完成接下来的工作。',
        ],
    ],

    'show' => [
        'created_by' => '作者：:author',
        'download' => '下载',
        'item' => [
            'cleared' => '已通过',
            'not_cleared' => '未通过',
        ],
        'no_diff_reduction' => [
            '_' => '若要解锁成就，则不能使用:link游玩谱面。',
            'link' => '降低难度的模组',
        ],
    ],

    'mode' => [
        'artist' => '艺术家/专辑',
        'chart' => '聚光灯',
        'featured' => '精选艺术家',
        'loved' => '社区喜爱计划',
        'standard' => '常规',
        'theme' => '主题',
        'tournament' => '锦标赛',
    ],

    'require_login' => [
        '_' => '需要 :link 才能下载',
        'link_text' => '登录',
    ],
];
