<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'header' => [
        'small' => 'サークルをクリックするだけでなく様々な方法で競うことができます。',
        'large' => 'コミュニティコンテスト',
    ],

    'index' => [
        'nav_title' => '一覧',
    ],

    'judge' => [
        'comments' => 'コメント',
        'hide_judged' => '判定済みのエントリーを非表示にする',
        'nav_title' => '判定',
        'no_current_vote' => 'あなたはまだ投票していません。',
        'update' => '更新',
        'validation' => [
            'missing_score' => 'スコアが欠損している',
            'contest_vote_judged' => '判定されたコンテストに投票できません',
        ],
        'voted' => 'あなたはすでにこのエントリに投票しました。',
    ],

    'judge_results' => [
        '_' => '判定結果',
        'creator' => '作成者',
        'score' => 'スコア',
        'score_std' => '',
        'total_score' => '合計スコア',
        'total_score_std' => '',
    ],

    'voting' => [
        'judge_link' => 'あなたはこのコンテストの審査員です、ここでエントリーを審査しましょう！',
        'judged_notice' => 'このコンテストでは審査制度を使用し、審査員が応募作品を審査しています。',
        'login_required' => '投票するにはログインが必要です。',
        'over' => 'このコンテストの投票期間は終了しました。',
        'show_voted_only' => '投票済みを表示',

        'best_of' => [
            'none_played' => "このコンテストに該当するビートマップをプレイした事がないようです！",
        ],

        'button' => [
            'add' => '投票',
            'remove' => '投票を削除',
            'used_up' => '全ての投票権を使いました。',
        ],

        'progress' => [
            '_' => ':used / :max の投票を使用済み',
        ],

        'requirement' => [
            'playlist_beatmapsets' => [
                'incomplete_play' => '投票前に指定されたプレイリストのすべてのビートマップをプレイする必要があります。',
            ],
        ],
    ],

    'entry' => [
        '_' => 'エントリー',
        'login_required' => 'このコンテストにエントリーするにはログインが必要です。',
        'silenced_or_restricted' => '制限されている間やサイレンス中はコンテストにエントリーすることはできません。',
        'preparation' => 'このコンテストは準備中です。しばらくお待ちください！',
        'drop_here' => 'エントリーをここにドロップ！',
        'download' => '.oszをダウンロード',

        'wrong_type' => [
            'art' => 'このコンテストは.jpgか.png拡張子のファイルしか受け付けていません。',
            'beatmap' => 'このコンテストは.osu拡張子のファイルしか受け付けていません。',
            'music' => 'このコンテストは.mp3拡張子のファイルしか受け付けていません。',
        ],

        'wrong_dimensions' => 'このコンテストのエントリーは:widthx:heightでなければいけません',
        'too_big' => 'このコンテストのエントリーの上限は:limitです。',
    ],

    'beatmaps' => [
        'download' => 'エントリーをダウンロード',
    ],

    'vote' => [
        'list' => '投票',
        'count' => ':count_delimited 票',
        'points' => ':count_delimited ポイント',
        'points_float' => '',
    ],

    'dates' => [
        'ended' => '終了日 :date',
        'ended_no_date' => '終了',

        'starts' => [
            '_' => '開始日 :date',
            'soon' => '間もなく™',
        ],
    ],

    'states' => [
        'entry' => 'エントリー受付中',
        'voting' => '投票期間中',
        'results' => '結果発表',
    ],
];
