<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'edit' => [
        'title_compact' => 'налаштування',
        'username' => 'ім\'я користувача',

        'avatar' => [
            'title' => 'Аватар',
            'rules' => 'Будь ласка, переконайтесь що Ваш аватар відповідає :link.<br/>Це означає що він повинен <strong>підходити людям, будь-якого віку</strong>. Тобто: не містити наготи, нецензурної лексики або непристойного вмісту.',
            'rules_link' => 'правила спільноти',
        ],

        'email' => [
            'new' => 'нова пошта',
            'new_confirmation' => 'підтвердження пошти',
            'title' => 'Ел. пошта',
        ],

        'legacy_api' => [
            'api' => 'api',
            'irc' => 'irc',
            'title' => 'Старий API',
        ],

        'password' => [
            'current' => 'поточний пароль',
            'new' => 'новий пароль',
            'new_confirmation' => 'підтвердження паролю',
            'title' => 'Пароль',
        ],

        'profile' => [
            'title' => 'Профіль',

            'user' => [
                'user_discord' => '',
                'user_from' => 'місце проживання',
                'user_interests' => 'хобі',
                'user_occ' => 'рід занять',
                'user_twitter' => '',
                'user_website' => 'вебсайт',
            ],
        ],

        'signature' => [
            'title' => 'Підпис на форумі',
            'update' => 'оновити',
        ],
    ],

    'notifications' => [
        'beatmapset_discussion_qualified_problem' => 'отримувати сповіщення про нові проблеми у кваліфікованих мапах для перелічених режимів',
        'beatmapset_disqualify' => 'отримувати сповіщення коли мапи перелічених режимів будуть дискваліфіковані',
        'comment_reply' => 'отримувати сповіщення про відповіді на ваші коментарі',
        'title' => 'Сповіщення',
        'topic_auto_subscribe' => 'автоматично вмикати сповіщення для нових тем на форумі, коли ви їх створюєте',

        'options' => [
            '_' => 'метод отримання',
            'beatmap_owner_change' => 'гостьова складність',
            'beatmapset:modding' => 'моддинг мап',
            'channel_message' => 'особисті повідомлення',
            'comment_new' => 'нові коментарі',
            'forum_topic_reply' => 'відповідь в темі',
            'mail' => 'пошта',
            'mapping' => 'автор бітмапи',
            'push' => 'push',
            'user_achievement_unlock' => 'медаль користувача розблоковано',
        ],
    ],

    'oauth' => [
        'authorized_clients' => 'авторизовані клієнти',
        'own_clients' => 'власні клієнти',
        'title' => 'OAuth',
    ],

    'options' => [
        'beatmapset_show_nsfw' => 'приховати попередження щодо відвертого вмісту в мапах',
        'beatmapset_title_show_original' => 'показувати метадані мовою оригіналу',
        'title' => 'Налаштування',

        'beatmapset_download' => [
            '_' => 'стандартний тип завантаження мапи',
            'all' => 'з відео, якщо доступно',
            'direct' => 'відкрити в osu!direct',
            'no_video' => 'без відео',
        ],
    ],

    'playstyles' => [
        'keyboard' => 'клавіатура',
        'mouse' => 'мишка',
        'tablet' => 'графічний планшет',
        'title' => 'Стилі гри',
        'touch' => 'сенсорний екран',
    ],

    'privacy' => [
        'friends_only' => 'не отримувати приватні повідомлення від людей, яких немає у моєму списку друзів',
        'hide_online' => 'приховувати вашу присутність "В мережі"',
        'title' => 'Політика конфіденційності',
    ],

    'security' => [
        'current_session' => 'поточна',
        'end_session' => 'Завершити Сесію ',
        'end_session_confirmation' => 'Сесія на цьому пристрої буде негайно завершена. Ви впевнені?',
        'last_active' => 'Остання активність:',
        'title' => 'Безпека',
        'web_sessions' => 'веб-сеанси',
    ],

    'update_email' => [
        'update' => 'оновити',
    ],

    'update_password' => [
        'update' => 'оновити',
    ],

    'verification_completed' => [
        'text' => 'Тепер ви можете закрити цю вкладку/вікно',
        'title' => 'Верифікацію завершено',
    ],

    'verification_invalid' => [
        'title' => 'Неправильне або застаріле посилання верифікації',
    ],
];
