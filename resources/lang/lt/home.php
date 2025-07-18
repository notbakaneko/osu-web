<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'landing' => [
        'download' => 'Atsisiųskite dabar',
        'online' => '<strong>:players</strong> dabar tinkle tarp <strong>:games</strong> žaidimų',
        'peak' => 'Daugiausiai pasiekta :count prisijungusių vartotojų',
        'players' => '<strong>:count</strong> registruoti žaidėjai',
        'title' => 'sveiki',
        'see_more_news' => 'pažiūrėti daugiau naujienų',

        'slogan' => [
            'main' => 'geriausias nemokamas laimėti ritmo žaidimas',
            'sub' => 'ritmas yra tik per vieną paspaudimą nuo tavęs',
        ],
    ],

    'search' => [
        'advanced_link' => 'Išplėstinė paieška',
        'button' => 'Ieškoti',
        'empty_result' => 'Nieko nerasta!',
        'keyword_required' => 'Reikalingas paieškos raktažodis',
        'placeholder' => 'rašykite, kad ieškoti',
        'title' => 'paieška',

        'artist_track' => [
            'more_simple' => '',
        ],
        'beatmapset' => [
            'login_required' => 'Prisijunkite, kad ieškoti bitmapų',
            'more' => ':count dar beatmap\'ų paieškos rezultatų',
            'more_simple' => ' Žiūrėti daugiau bitmapų paieškos rezultatų',
            'title' => 'Beatmap\'ai',
        ],

        'forum_post' => [
            'all' => 'Visi forumai',
            'link' => 'Ieškoti forume',
            'login_required' => 'Prisijunkite, kad ieškoti forume',
            'more_simple' => ' Žiūrėti daugiau forumo paieškos rezultatų',
            'title' => 'Forumas',

            'label' => [
                'forum' => 'ieškoti forumuose',
                'forum_children' => 'įtraukti poforumius',
                'include_deleted' => 'įtraukti ištrintus įrašus',
                'topic_id' => 'tema #',
                'username' => 'autorius',
            ],
        ],

        'mode' => [
            'all' => 'visi',
            'artist_track' => '',
            'beatmapset' => 'beatmap\'as',
            'forum_post' => 'forumas',
            'team' => '',
            'user' => 'žaidėjas',
            'wiki_page' => 'wiki',
        ],

        'team' => [
            'more_simple' => '',
        ],

        'user' => [
            'login_required' => 'Prisijunkite, kad ieškot vartotojus',
            'more' => ':count daugiau žaidėjų paieškos rezultatų',
            'more_simple' => ' Žiūrėti daugiau žaidėjų paieškos rezultatų',
            'more_hidden' => 'Žaidėjo paieškos yra limituotos :max žaidėjų. Pabandykite išgryninti paieškos užklausa.',
            'title' => 'Žaidėjai',
        ],

        'wiki_page' => [
            'link' => 'Ieškoti tarp wiki',
            'more_simple' => ' Žiūrėti daugiau wiki paieškos rezultatų',
            'title' => 'Wiki',
        ],
    ],

    'download' => [
        'action' => 'Atsisiųsti osu!',
        'action_lazer' => 'Atsisiųsti osu!(lazer)',
        'action_lazer_description' => 'kitas didesnis osu! atnaujinimas.',
        'action_lazer_info' => 'peržiūrėk šį puslapį dėl papildomos informacijos',
        'action_lazer_title' => 'išbandyk osu!(lazer)',
        'action_title' => 'atsisiųsti osu!',
        'for_os' => 'skirtas :os',
        'macos-fallback' => 'macOS vartotojams',
        'mirror' => 'dubliavimas',
        'or' => 'arba',
        'os_version_or_later' => ':os_version arba vėlesnė',
        'other_os' => 'kitos platformos',
        'quick_start_guide' => 'pagalba pradedančiam',
        'tagline' => "gaukime <br> ko jums reikia pradžiai!",
        'video-guide' => 'vaizdo gidas',

        'help' => [
            '_' => 'jei patiriate problemas paleidžiant žaidimą ar registruojantis paskyrai, :help_forum_link arba :support_button.',
            'help_forum_link' => 'pažiūrėti pagalbos foruma',
            'support_button' => 'susisiekti su pagalba',
        ],

        'os' => [
            'windows' => 'Windows sistemai',
            'macos' => 'MacOS sistemai',
            'linux' => 'Linux sistemai',
        ],
        'steps' => [
            'register' => [
                'title' => 'susikurk paskyrą',
                'description' => 'sek instrukcijas paleidus žaidimą, kad prisijungti ar susikurti naują paskyra',
            ],
            'download' => [
                'title' => 'atsisiųsk žaidimą',
                'description' => 'paspauskt mygtyką viršuje, kad atsisiųsti diegimo programa, ir paleisk!',
            ],
            'beatmaps' => [
                'title' => 'gauk beatmap\'ų',
                'description' => [
                    '_' => ':browse žaidėjų sukurtus beatmap\'us ir pradėk juos žaisti!',
                    'browse' => 'naršyti',
                ],
            ],
        ],
    ],

    'user' => [
        'title' => 'ataskaitų sritis',
        'news' => [
            'title' => 'Naujienos',
            'error' => 'Klaida kraunant naujienas, pabandykite perkrauti puslapį?...',
        ],
        'header' => [
            'stats' => [
                'friends' => 'Prisijungę Draugai',
                'games' => 'Žaidimai',
                'online' => 'Prisijungę vartotojai',
            ],
        ],
        'beatmaps' => [
            'daily_challenge' => '',
            'new' => 'Nauji Reitinguoti Beatmap\'ai',
            'popular' => 'Populiarūs Beatmap\'ai',
            'by_user' => 'sukūrė :user',
            'resets' => '',
        ],
        'buttons' => [
            'download' => 'Atsisiųsti osu!',
            'support' => 'Paremti osu!',
            'store' => 'osu!parduotuvė',
        ],
    ],
];
