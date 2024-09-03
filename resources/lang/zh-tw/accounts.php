<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'edit' => [
        'title_compact' => '設定',
        'username' => '使用者名稱',

        'avatar' => [
            'title' => '編輯頭像',
            'reset' => '重置',
            'rules' => '請確保您的頭像符合 :link.<br/>這意味著必須 <strong>適合所有年齡</strong>. i.e. 沒有裸露，褻瀆或暗示性的內容。',
            'rules_link' => '社群規則',
        ],

        'email' => [
            'new' => '新電子郵件地址',
            'new_confirmation' => '再次輸入電子郵件地址',
            'title' => '電子郵件',
            'locked' => [
                '_' => '如欲修改你的電子郵件地址，請聯絡 :accounts。',
                'accounts' => '帳戶支援團隊',
            ],
        ],

        'legacy_api' => [
            'api' => 'api',
            'irc' => 'irc',
            'title' => '舊版API',
        ],

        'password' => [
            'current' => '目前密碼',
            'new' => '新密碼',
            'new_confirmation' => '再次輸入新密碼',
            'title' => '密碼',
        ],

        'profile' => [
            'country' => '國家',
            'title' => '個人資料',

            'country_change' => [
                '_' => "您的帳戶資料上所顯示的國家似乎與您當前的居住地不匹配。:update_link。",
                'update_link' => '更新為 :country',
            ],

            'user' => [
                'user_discord' => '',
                'user_from' => '目前所在地',
                'user_interests' => '喜好',
                'user_occ' => '職業',
                'user_twitter' => '',
                'user_website' => '個人網站',
            ],
        ],

        'signature' => [
            'title' => '簽名',
            'update' => '更新',
        ],
    ],

    'github_user' => [
        'info' => "如果你是 osu! 開源倉庫的貢獻者，在這裡連結你的 GitHub 帳戶以便使你的更新日誌條目與這個 osu! 個人資料相連結。如果欲連結的 GitHub 帳戶在 osu! 開源倉庫上沒有歷史記錄，則無法連結。",
        'link' => '連結 GitHub 帳戶',
        'title' => 'GitHub',
        'unlink' => '取消連結 GitHub 帳戶',

        'error' => [
            'already_linked' => '這個 GitHub 帳戶已經連結至另一位玩家的帳戶上。',
            'no_contribution' => '無法連結在 osu! 開源倉庫中無任何貢獻記錄的 GitHub 帳戶。',
            'unverified_email' => '請先在 GitHub 上驗證你的首要電子郵件，然後再次嘗試連結帳戶。',
        ],
    ],

    'notifications' => [
        'beatmapset_discussion_qualified_problem' => '在以下模式的 qualified 圖譜上接收新問題通知',
        'beatmapset_disqualify' => '在以下模式的圖譜被標記為取消提名時收到通知',
        'comment_reply' => '在您的留言被回覆時收到通知',
        'title' => '通知',
        'topic_auto_subscribe' => '自動啟用自己創建的主題的通知',

        'options' => [
            '_' => '傳送選項',
            'beatmap_owner_change' => '客串難度',
            'beatmapset:modding' => '圖譜製作',
            'channel_message' => '私人訊息',
            'comment_new' => '新評論',
            'forum_topic_reply' => '主題回覆',
            'mail' => '郵箱',
            'mapping' => '圖譜製作者',
            'push' => '推送',
        ],
    ],

    'oauth' => [
        'authorized_clients' => '已授權客戶端',
        'own_clients' => '擁有的客戶端',
        'title' => 'OAuth',
    ],

    'options' => [
        'beatmapset_show_nsfw' => '隱藏兒童不宜圖譜的警告',
        'beatmapset_title_show_original' => '以原語言顯示圖譜資料',
        'title' => '選項',

        'beatmapset_download' => [
            '_' => '預設圖譜下載類型',
            'all' => '包含影片',
            'direct' => '在osu!direct中查看',
            'no_video' => '不包含影片',
        ],
    ],

    'playstyles' => [
        'keyboard' => '鍵盤',
        'mouse' => '滑鼠',
        'tablet' => '繪圖板',
        'title' => '遊戲方式',
        'touch' => '觸控螢幕',
    ],

    'privacy' => [
        'friends_only' => '過濾來自好友以外的訊息',
        'hide_online' => '隱藏在線狀態',
        'title' => '隱私政策',
    ],

    'security' => [
        'current_session' => '目前',
        'end_session' => '終止會話',
        'end_session_confirmation' => '你確定要立刻結束該設備上的會話嗎？',
        'last_active' => '上次使用：',
        'title' => '安全',
        'web_sessions' => '瀏覽器會話',
    ],

    'update_email' => [
        'update' => '更新',
    ],

    'update_password' => [
        'update' => '更新',
    ],

    'verification_completed' => [
        'text' => '您現在可以關閉此分頁/視窗',
        'title' => '驗證已經完成',
    ],

    'verification_invalid' => [
        'title' => '無效或過期的驗證連結',
    ],
];
