<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'availability' => [
        'disabled' => 'Эту карту пока что нельзя скачать.',
        'parts-removed' => 'Некоторые части этой карты были удалены по требованию автора или правообладателей.',
        'more-info' => 'Нажмите сюда, чтобы узнать подробности.',
        'rule_violation' => 'Некоторые части этой карты были удалены после того, как их посчитали неприемлемыми для osu!.',
    ],

    'cover' => [
        'deleted' => 'Удалённая карта',
    ],

    'download' => [
        'limit_exceeded' => 'Меньше качай и больше играй.',
        'no_mirrors' => 'Нет доступных загрузочных серверов.',
    ],

    'featured_artist_badge' => [
        'label' => 'Избранный исполнитель',
    ],

    'index' => [
        'title' => 'Библиотека карт',
        'guest_title' => 'Карты',
    ],

    'panel' => [
        'empty' => 'нет карт',

        'download' => [
            'all' => 'скачать',
            'video' => 'скачать с видео',
            'no_video' => 'скачать без видео',
            'direct' => 'открыть в osu!direct',
        ],
    ],

    'nominate' => [
        'bng_limited_too_many_rulesets' => 'Номинаторы, находящиеся на испытательном сроке, не могут номинировать несколько режимов игры за раз.',
        'full_nomination_required' => 'Вы должны быть полноправным номинатором, чтобы внести решающий голос по этому режиму игры.',
        'hybrid_requires_modes' => 'Чтобы номинировать карту-гибрид, необходимо выбрать по крайней мере один режим игры.',
        'incorrect_mode' => 'У вас недостаточно прав для номинации по режиму :mode',
        'invalid_limited_nomination' => 'Эта карта имеет недопустимые номинации и не может быть квалифицирована.',
        'invalid_ruleset' => 'Недопустимые режимы игры для этой номинации.',
        'too_many' => 'Требования к номинации уже выполнены.',
        'too_many_non_main_ruleset' => 'Требование номинации неосновного режима игры уже было выполнено.',

        'dialog' => [
            'confirmation' => 'Вы уверены, что хотите номинировать эту карту?',
            'different_nominator_warning' => 'Повторная квалификация этой карты парой номинаторов, отличающейся от изначальной, сбросит её позицию в очереди получения рейтинга.',
            'header' => 'Номинировать карту',
            'hybrid_warning' => 'примечание: вы можете номинировать лишь один раз, так что убедитесь, что номинируете все необходимые режимы игры',
            'current_main_ruleset' => 'Текущий основной режим игры: :ruleset',
            'which_modes' => 'Номинировать для каких режимов?',
        ],
    ],

    'nsfw_badge' => [
        'label' => '18+',
    ],

    'show' => [
        'discussion' => 'Обсуждение',

        'admin' => [
            'full_size_cover' => 'Открыть обложку в оригинальном размере',
        ],

        'deleted_banner' => [
            'title' => 'Эта карта была удалена.',
            'message' => '(это видят только модераторы)',
        ],

        'details' => [
            'by_artist' => 'от :artist',
            'favourite' => 'добавить в избранные',
            'favourite_login' => 'войдите в аккаунт, чтобы добавить эту карту в избранные',
            'logged-out' => 'вы должны войти в аккаунт, чтобы начать скачивать карты!',
            'mapped_by' => 'автор :mapper',
            'mapped_by_guest' => 'гостевая сложность от :mapper',
            'unfavourite' => 'удалить из избранных',
            'updated_timeago' => 'обновлена :timeago',

            'download' => [
                '_' => 'Скачать',
                'direct' => '',
                'no-video' => 'без видео',
                'video' => 'с видео',
            ],

            'login_required' => [
                'bottom' => 'чтобы скачать',
                'top' => 'Войдите',
            ],
        ],

        'details_date' => [
            'approved' => 'одобрена :timeago',
            'loved' => 'стала любимой :timeago',
            'qualified' => 'квалифицирована :timeago',
            'ranked' => 'стала рейтинговой :timeago',
            'submitted' => 'опубликована :timeago',
            'updated' => 'обновлена :timeago',
        ],

        'favourites' => [
            'limit_reached' => 'У вас слишком много избранных карт! Пожалуйста, удалите некоторые из них и попробуйте снова.',
        ],

        'hype' => [
            'action' => 'Хайпаните эту карту, если Вам понравилось её играть, чтобы помочь ей стать <strong>Рейтинговой</strong>.',

            'current' => [
                '_' => 'Эта карта сейчас :status.',

                'status' => [
                    'pending' => 'на рассмотрении',
                    'qualified' => 'квалифицирована',
                    'wip' => 'в разработке',
                ],
            ],

            'disqualify' => [
                '_' => 'Если вы обнаружили проблему у этой карты, пожалуйста, дисквалифицируйте её :link.',
            ],

            'report' => [
                '_' => 'Если вы обнаружили проблему, связанную с этой картой, пожалуйста, сообщите об этом :link, чтобы оповестить команду osu!.',
                'button' => 'Сообщить о проблеме',
                'link' => 'здесь',
            ],
        ],

        'info' => [
            'description' => 'Описание',
            'genre' => 'Жанр',
            'language' => 'Язык',
            'mapper_tags' => 'Теги от маппера',
            'no_scores' => 'Данные всё ещё обрабатываются...',
            'nominators' => 'Номинаторы',
            'nsfw' => 'Откровенное содержание',
            'offset' => 'Онлайн-оффсет',
            'points-of-failure' => 'Диаграмма провалов',
            'source' => 'Источник',
            'storyboard' => 'Со сторибордом',
            'success-rate' => 'Процент прохождений',
            'user_tags' => 'Теги от игроков',
            'video' => 'С видео',
        ],

        'nsfw_warning' => [
            'details' => 'Эта карта содержит откровенное или оскорбительное содержание. Вы уверены, что хотите продолжить?',
            'title' => 'Откровенное содержание',

            'buttons' => [
                'disable' => 'Отключить предупреждение',
                'listing' => 'Библиотека карт',
                'show' => 'Показать',
            ],
        ],

        'scoreboard' => [
            'achieved' => 'достигнут :when',
            'country' => 'Рейтинг по стране',
            'error' => 'Не удалось загрузить таблицу рекордов',
            'friend' => 'Рейтинг среди друзей',
            'global' => 'Мировой рейтинг',
            'supporter-link' => 'Нажмите <a href=":link">сюда</a> для просмотра всех возможностей, которые вы можете получить!',
            'supporter-only' => 'Приобретите тег osu!supporter, чтобы получить доступ к рейтингу по друзьям, странам или модам!',
            'team' => 'Командный рейтинг',
            'title' => 'Табло',

            'headers' => [
                'accuracy' => 'Точность',
                'combo' => 'Макс. комбо',
                'miss' => 'Промах',
                'mods' => 'Моды',
                'pin' => 'В профиле',
                'player' => 'Игрок',
                'pp' => '',
                'rank' => 'Место',
                'score' => 'Очки',
                'score_total' => 'Всего очков',
                'time' => 'Дата',
            ],

            'no_scores' => [
                'country' => 'Никто из вашей страны ещё не сыграл эту карту!',
                'friend' => 'Никто из ваших друзей ещё не сыграл эту карту!',
                'global' => 'Пока рекордов нет. Поставите первый?',
                'loading' => 'Рекорды загружаются...',
                'team' => 'Никто из вашей команды ещё не поставил рекорд на этой карте!',
                'unranked' => 'Безрейтинговая карта.',
            ],
            'score' => [
                'first' => 'Лидирует',
                'own' => 'Ваш рекорд',
            ],
            'supporter_link' => [
                '_' => 'Нажмите :here для просмотра всех возможностей, которые вы можете получить!',
                'here' => 'сюда',
            ],
        ],

        'stats' => [
            'cs' => 'Размер нот',
            'cs-mania' => 'Количество клавиш',
            'drain' => 'Скорость потери HP',
            'accuracy' => 'Точность',
            'ar' => 'Скорость появления нот',
            'stars' => 'Сложность',
            'total_length' => 'Длительность (длительность потери HP: :hit_length)',
            'bpm' => 'BPM',
            'count_circles' => 'Количество нот',
            'count_sliders' => 'Количество слайдеров',
            'offset' => 'Значение оффсета: :offset',
            'user-rating' => 'Оценки пользователей',
            'rating-spread' => 'Распределение оценок',
            'nominations' => 'Номинации',
            'playcount' => 'Количество игр',
        ],

        'status' => [
            'ranked' => 'Рейтинговая',
            'approved' => 'Одобренная',
            'loved' => 'Любимая',
            'qualified' => 'Квалифицированная',
            'wip' => 'В разработке',
            'pending' => 'На рассмотрении',
            'graveyard' => 'Заброшенная',
        ],
    ],

    'spotlight_badge' => [
        'label' => 'Из чарта',
    ],
];
