<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'landing' => [
        'download' => 'Prenesi zdaj',
        'online' => '<strong>:players</strong> trenutno aktivnih v <strong>:games</strong> igrah',
        'peak' => 'Vrhunec, :count aktivnih igralcev',
        'players' => '<strong>:count</strong> registriranih igralcev',
        'title' => 'dobrodošli',
        'see_more_news' => 'prikaži več novic',

        'slogan' => [
            'main' => 'najboljša brezplačna ritmična igra',
            'sub' => 'ritem je le en klik stran',
        ],
    ],

    'search' => [
        'advanced_link' => 'Napredno iskanje',
        'button' => 'Išči',
        'empty_result' => 'Ni najdenih rezultatov!',
        'keyword_required' => 'Potrebna je iskalna ključna beseda',
        'placeholder' => 'vpiši za iskanje',
        'title' => 'išči',

        'artist_track' => [
            'more_simple' => '',
        ],
        'beatmapset' => [
            'login_required' => 'Za brskanje beatmap se vpiši',
            'more' => ':count več najdenih rezultatov beatmap',
            'more_simple' => 'Prikaži več beatmap rezultatov',
            'title' => 'Beatmape',
        ],

        'forum_post' => [
            'all' => 'Vsi forumi',
            'link' => 'Išči forum',
            'login_required' => 'Vpiši se za iskanje foruma',
            'more_simple' => 'Prikaži več rezulatov foruma',
            'title' => 'Forum',

            'label' => [
                'forum' => 'išči v forumih',
                'forum_children' => 'vključi podforume',
                'include_deleted' => 'vključi izbrisane objave',
                'topic_id' => 'tema #',
                'username' => 'avtor',
            ],
        ],

        'mode' => [
            'all' => 'vse',
            'artist_track' => '',
            'beatmapset' => 'beatmap',
            'forum_post' => 'forum',
            'team' => '',
            'user' => 'igralec',
            'wiki_page' => 'wiki',
        ],

        'team' => [
            'more_simple' => '',
        ],

        'user' => [
            'login_required' => 'Vpiši se za iskanje igralcev',
            'more' => ':count več najdenih igralcev',
            'more_simple' => 'Prikaži več najdenih igralcev',
            'more_hidden' => 'Iskalnik igralcev je omejen na :max igralcev. Poskusi izboljšati poizvedbo iskanja.',
            'title' => 'Igralci',
        ],

        'wiki_page' => [
            'link' => 'Brskaj wiki',
            'more_simple' => 'Prikaži več wiki rezultatov',
            'title' => 'Wiki',
        ],
    ],

    'download' => [
        'action' => 'Prenesi osu!',
        'action_lazer' => 'Prenesi osu!(lazer)',
        'action_lazer_description' => 'naslednja glavna posodobitev osu!',
        'action_lazer_info' => 'preveri to stran za več informacij',
        'action_lazer_title' => 'poskusi osu!(lazer)',
        'action_title' => 'prenesi osu!',
        'for_os' => 'za :os',
        'macos-fallback' => 'macOS uporabniki',
        'mirror' => 'mirror',
        'or' => 'ali',
        'os_version_or_later' => ':os_version ali novejši',
        'other_os' => 'ostale platforme',
        'quick_start_guide' => 'navodila za hiter začetek',
        'tagline' => "pa<br>začnimo!",
        'video-guide' => 'video vodič',

        'help' => [
            '_' => 'če naletiš na težavo pri zagonu igre ali registraciji računa, :help_forum_link ali :support_button.',
            'help_forum_link' => 'preveri forum za pomoč',
            'support_button' => 'kontaktiraj podporo',
        ],

        'os' => [
            'windows' => 'za Windows',
            'macos' => 'za macOS',
            'linux' => 'za Linux',
        ],
        'steps' => [
            'register' => [
                'title' => 'pridobi račun',
                'description' => 'sledi navodilom ko zaženeš igro in se vpiši ali ustvari nov račun',
            ],
            'download' => [
                'title' => 'namesti igro',
                'description' => 'klikni gumb zgoraj za prenos namestitvenega programa, nato zaženi igro!',
            ],
            'beatmaps' => [
                'title' => 'pridobi beatmape',
                'description' => [
                    '_' => ':browse veliko knjižnico beatmap, ki so jih ustvarili igralci in začni igrati!',
                    'browse' => 'brskaj',
                ],
            ],
        ],
    ],

    'user' => [
        'title' => 'nadzorna plošča',
        'news' => [
            'title' => 'Novice',
            'error' => 'Napaka pri nalaganju novic, poskusi osvežiti stran?...',
        ],
        'header' => [
            'stats' => [
                'friends' => 'Online Prijatelji',
                'games' => 'Igre',
                'online' => 'Online Igralci',
            ],
        ],
        'beatmaps' => [
            'daily_challenge' => '',
            'new' => 'Nove Rankirane Beatmape',
            'popular' => 'Popularne Beatmape',
            'by_user' => 'od :user',
            'resets' => '',
        ],
        'buttons' => [
            'download' => 'Prenesi osu!',
            'support' => 'Podpri osu!',
            'store' => 'osu!store',
        ],
    ],
];
