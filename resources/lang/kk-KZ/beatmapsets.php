<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'availability' => [
        'disabled' => 'Бұл картаны қазір жүктеп алу мүмкін емес.',
        'parts-removed' => 'Бұл картаның бөліктері жасаушының немесе үшінші тарап құқық иесінің сұрауы бойынша жойылды.',
        'more-info' => 'Қосымша ақпарат алу үшін мына жерге кіріңіз.',
        'rule_violation' => 'Осы картадағы кейбір ішіндегісілер osu!-де пайдалануға жарамсыз деп шешілгеннен кейін жойылды.',
    ],

    'cover' => [
        'deleted' => 'Жойылған карта',
    ],

    'download' => [
        'limit_exceeded' => 'Баяу болыңыз, көбірек ойнаңыз.',
        'no_mirrors' => '',
    ],

    'featured_artist_badge' => [
        'label' => 'Таңдаулы Әртіс',
    ],

    'index' => [
        'title' => 'Карталар тізімі',
        'guest_title' => 'Карталар',
    ],

    'panel' => [
        'empty' => 'карталар жоқ',

        'download' => [
            'all' => 'жүктеу',
            'video' => 'бейнесімен жүктеу',
            'no_video' => 'бейнесіз жүктеу',
            'direct' => 'osu!direct-те ашу',
        ],
    ],

    'nominate' => [
        'bng_limited_too_many_rulesets' => '',
        'full_nomination_required' => '',
        'hybrid_requires_modes' => 'Гибрид картасы номинация алу үшін кемінде бір ойын режимін таңдауды талап етеді.',
        'incorrect_mode' => 'Сізде режимге номинация беру рұқсатыңыз жоқ: :mode',
        'invalid_limited_nomination' => '',
        'invalid_ruleset' => '',
        'too_many' => 'Номинация талабы орындалды.',
        'too_many_non_main_ruleset' => '',

        'dialog' => [
            'confirmation' => 'Осы картаны номинация беретініне сенімдісіз бе?',
            'header' => 'Картаға номинацияны беру',
            'hybrid_warning' => 'ескерту: сіз тек бір рет номинация бере аласыз, сондықтан сіз барлық қажетті ойын режимдеріне номинация беретін екеніңізге көз жеткізіңіз',
            'current_main_ruleset' => '',
            'which_modes' => 'Қай режимдерге номинация беру?',
        ],
    ],

    'nsfw_badge' => [
        'label' => 'Былапыт',
    ],

    'show' => [
        'discussion' => 'Пікірталас',

        'admin' => [
            'full_size_cover' => '',
        ],

        'deleted_banner' => [
            'title' => 'Бұл карта жойылды.',
            'message' => '(мұны тек модераторлар көре алады)',
        ],

        'details' => [
            'by_artist' => ':artist жасады',
            'favourite' => 'осы картаны ұнамдыларға қосу',
            'favourite_login' => 'осы картаны ұнамдыларға қосу үшін аккаунтыңызға кіріңіз',
            'logged-out' => 'кез келген карталарды жүктеп алу үшін аккаунтыңызға кіруіңіз керек!',
            'mapped_by' => ':mapper жасады',
            'mapped_by_guest' => ':mapper жасаған қонақ деңгейі',
            'unfavourite' => 'осы картаны ұнамдылардан жою',
            'updated_timeago' => 'соңғы жаңартылған: :timeago',

            'download' => [
                '_' => 'Жүктеу',
                'direct' => '',
                'no-video' => 'бейнесіз',
                'video' => 'бейнемен',
            ],

            'login_required' => [
                'bottom' => 'қосымша мүмкіндіктерге қол жеткізу үшін',
                'top' => 'Кіру',
            ],
        ],

        'details_date' => [
            'approved' => 'қабылданды :timeago',
            'loved' => 'ұнамды статусы берілді :timeago',
            'qualified' => 'квалификация берілді :timeago',
            'ranked' => 'рейтингті статусы берілді :timeago',
            'submitted' => 'жүктелді :timeago',
            'updated' => 'соңғы жаңартылған: :timeago',
        ],

        'favourites' => [
            'limit_reached' => 'Сізде тым көп ұнамды карталар бар! Қайталаудан бұрын кейбіреулерін ұнамдылардан алып тастаңыз.',
        ],

        'hype' => [
            'action' => 'Бұл картаны <strong>Рейтингті</strong> күйіне өтуге көмектесу үшін және ойнағаныңыз ұнаса хайп қалдырыңыз.',

            'current' => [
                '_' => 'Бұл карта қазіргі уақытта :status.',

                'status' => [
                    'pending' => 'қарастырылуда',
                    'qualified' => 'квалификацияланған',
                    'wip' => 'жұмыс орындалуда',
                ],
            ],

            'disqualify' => [
                '_' => 'Егер сіз осы карта бойынша мәселе тапсаңыз, оны дисквалификациялаңыз :link.',
            ],

            'report' => [
                '_' => 'Егер сіз осы картасына қатысты мәселе тапсаңыз, оны топқа хабарлаңыз :link.',
                'button' => 'Мәселе жайлы хабарлау',
                'link' => 'осында',
            ],
        ],

        'info' => [
            'description' => 'Сипаттамасы',
            'genre' => 'Жанры',
            'language' => 'Тілі',
            'no_scores' => 'Деректері әлі есептелуде...',
            'nominators' => 'Номинаторлар',
            'nsfw' => 'Былапыт мазмұны',
            'offset' => 'Онлай оффсеті',
            'points-of-failure' => 'Сәтсіздік жерлері',
            'source' => 'Дереккөзі',
            'storyboard' => 'Бұл картада сториборды бар',
            'success-rate' => 'Сәттілік Деңгейі',
            'tags' => 'Тегтер',
            'video' => 'Бұл картада бейнесі бар',
        ],

        'nsfw_warning' => [
            'details' => 'Бұл картада былапыт, сөгіс немесе алаңдататын мазмұны бар. Оны бәрібір көргіңіз келе ме?',
            'title' => 'Былапыт мазмұны',

            'buttons' => [
                'disable' => 'Ескертуді өшіру',
                'listing' => 'Карталар тізімі',
                'show' => 'Көрсету',
            ],
        ],

        'scoreboard' => [
            'achieved' => 'қойылған :when',
            'country' => 'Ел рейтингі',
            'error' => 'Рейтинг жүктеуі орындалмады',
            'friend' => 'Достар Рейтингі',
            'global' => 'Ғаламтық Рейтинг',
            'supporter-link' => 'Сіз алатын барлық керемет мүмкіндіктерді көру үшін <a href=":link">мына жерге</a> басыңыз!',
            'supporter-only' => 'Досқа, елге немесе мод-арнайы рейтингтерге қол жеткізу үшін сіз osu! қолдаушысы болуыңыз керек!',
            'title' => 'Нәтиже тақтасы',

            'headers' => [
                'accuracy' => 'Дәлдік',
                'combo' => 'Максималды Комбо',
                'miss' => 'Қате',
                'mods' => 'Модтар',
                'pin' => 'Бекіту',
                'player' => 'Ойыншы',
                'pp' => '',
                'rank' => 'Рангі',
                'score' => 'Нәтиже',
                'score_total' => 'Жалпы нәтиже',
                'time' => 'Уақыты',
            ],

            'no_scores' => [
                'country' => 'Әзірше еліңізден ешкім бұл картада нәтиже қойған жоқ!',
                'friend' => 'Әзірше достарыңыздан ешкім бұл картада нәтиже қойған жоқ!',
                'global' => 'Әлі нәтижелер жоқ. Мүмкін сіз біраз орнатуға тырысқыңыз келеді?',
                'loading' => 'Нәтижелер жүктелуде...',
                'unranked' => 'Рейтингі жоқ карта.',
            ],
            'score' => [
                'first' => 'Жетекші болуда',
                'own' => 'Сіздің ең жақсысы',
            ],
            'supporter_link' => [
                '_' => 'Сіз алатын барлық керемет мүмкіндіктерді көру үшін :here басыңыз!',
                'here' => 'осында',
            ],
        ],

        'stats' => [
            'cs' => 'Шеңбер өлшемі',
            'cs-mania' => 'Нота саны',
            'drain' => 'HP жүдеуі',
            'accuracy' => 'Дәлдігі',
            'ar' => 'Жақындау жылдамдықтың мөлшері',
            'stars' => 'Жұлдыз рейтингі',
            'total_length' => 'Ұзындығы (Жүдеу ұзындығы: :hit_length)',
            'bpm' => 'BPM',
            'count_circles' => 'Шеңбер Саны',
            'count_sliders' => 'Слайдер Саны',
            'offset' => 'Онлайн оффсеті: :offset',
            'user-rating' => 'Пайдаланушы рейтингі',
            'rating-spread' => 'Рейтингтің таралуы',
            'nominations' => 'Номинациялар',
            'playcount' => 'Ойын саны',
        ],

        'status' => [
            'ranked' => 'Рейтингілік',
            'approved' => 'Қабылданған',
            'loved' => 'Ұнамды',
            'qualified' => 'Квалификацияланған',
            'wip' => 'Жұмыс орындалуда',
            'pending' => 'Қарастырылуда',
            'graveyard' => 'Тасталынған',
        ],
    ],

    'spotlight_badge' => [
        'label' => 'Чарт',
    ],
];
