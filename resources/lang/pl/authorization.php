<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'play_more' => 'A może zamiast tego pograsz trochę w osu!?',
    'require_login' => 'Zaloguj się, aby kontynuować.',
    'require_verification' => 'Przejdź proces weryfikacji, aby kontynuować.',
    'restricted' => "Nie możesz tego zrobić podczas blokady konta.",
    'silenced' => "Nie możesz tego zrobić podczas uciszenia.",
    'unauthorized' => 'Odmowa dostępu.',

    'beatmap_discussion' => [
        'destroy' => [
            'is_hype' => 'Nie możesz cofnąć nagłośnienia.',
            'has_reply' => 'Nie możesz usunąć dyskusji z odpowiedziami',
        ],
        'nominate' => [
            'exhausted' => 'Osiągnięto dzienny limit nominacji, spróbuj ponownie jutro.',
            'incorrect_state' => 'Wystąpił błąd podczas wykonywania tej czynności, spróbuj odświeżyć stronę.',
            'owner' => "Nie możesz nominować własnej beatmapy.",
            'set_metadata' => 'Musisz ustawić gatunek i język przed nominowaniem beatmapy.',
        ],
        'resolve' => [
            'not_owner' => 'Tylko autor wątku i twórca beatmapy mogą zakończyć dyskusję.',
        ],

        'store' => [
            'mapper_note_wrong_user' => 'Adnotacje mogą być dodawane tylko przez twórcę beatmapy, nominatora lub członka NAT.',
        ],

        'vote' => [
            'bot' => "Nie możesz głosować w dyskusji utworzonej przez bota",
            'limit_exceeded' => 'Zaczekaj, zanim zagłosujesz ponownie',
            'owner' => "Nie możesz głosować we własnej dyskusji.",
            'wrong_beatmapset_state' => 'Możesz głosować tylko w dyskusjach do oczekujących beatmap.',
        ],
    ],

    'beatmap_discussion_post' => [
        'destroy' => [
            'not_owner' => 'Możesz usuwać tylko swoje posty.',
            'resolved' => 'Nie możesz usunąć posta z rozwiązanej dyskusji.',
            'system_generated' => 'Nie możesz usunąć automatycznie wygenerowanego posta.',
        ],

        'edit' => [
            'not_owner' => 'Tylko autor posta może go edytować.',
            'resolved' => 'Nie możesz edytować posta z rozwiązanej dyskusji.',
            'system_generated' => 'Nie możesz edytować automatycznie wygenerowanego posta.',
        ],
    ],

    'beatmapset' => [
        'discussion_locked' => 'Tworzenie dyskusji dla tej beatmapy zostało zablokowane.',

        'metadata' => [
            'nominated' => 'Nie możesz zmienić metadanych nominowanej beatmapy. Skontaktuj się z członkiem BN lub NAT, jeśli uważasz, że są one ustawione nieprawidłowo.',
        ],
    ],

    'beatmap_tag' => [
        'store' => [
            'no_score' => 'Musisz ustanowić wynik na beatmapie, aby dodać do niej tag.',
        ],
    ],

    'chat' => [
        'blocked' => 'Nie możesz wysłać wiadomości do użytkownika, którego blokujesz lub który cię blokuje.',
        'friends_only' => 'Ten użytkownik blokuje wiadomości od osób spoza listy znajomych.',
        'moderated' => 'Ten kanał jest obecnie w trybie tylko dla moderatorów.',
        'no_access' => 'Nie masz dostępu do tego kanału.',
        'no_announce' => 'Nie posiadasz uprawnień do zamieszczania ogłoszeń.',
        'receive_friends_only' => 'Ten użytkownik może nie być w stanie odpowiedzieć, ponieważ blokujesz prywatne wiadomości od osób spoza listy znajomych.',
        'restricted' => 'Nie możesz wysyłać wiadomości po tym, jak twoje konto zostało uciszone, ograniczone lub zablokowane.',
        'silenced' => 'Nie możesz wysyłać wiadomości po tym, jak twoje konto zostało uciszone, ograniczone lub zablokowane.',
    ],

    'comment' => [
        'store' => [
            'disabled' => 'Komentarze są wyłączone',
        ],
        'update' => [
            'deleted' => "Nie możesz edytować usuniętego posta.",
        ],
    ],

    'contest' => [
        'judging_not_active' => 'Faza oceniania prac w tym konkursie nie jest aktywna.',
        'voting_over' => 'Nie możesz zmienić swojego głosu po zakończeniu głosowania.',

        'entry' => [
            'limit_reached' => 'Osiągnięto limit zgłoszeń dla tego konkursu',
            'over' => 'Dziękujemy za zgłoszenia! Przesyłanie prac zakończyło się i wkrótce rozpocznie się głosowanie.',
        ],
    ],

    'forum' => [
        'moderate' => [
            'no_permission' => 'Nie posiadasz uprawnień do moderowania tego forum.',
        ],

        'post' => [
            'delete' => [
                'only_last_post' => 'Tylko ostatni post może zostać usunięty.',
                'locked' => 'Nie możesz usuwać postów w zamkniętym wątku.',
                'no_forum_access' => 'Nie posiadasz dostępu do tego forum.',
                'not_owner' => 'Tylko autor posta może go usunąć.',
            ],

            'edit' => [
                'deleted' => 'Nie możesz edytować usuniętego posta.',
                'locked' => 'Ten post jest chroniony przed edycją.',
                'no_forum_access' => 'Nie posiadasz dostępu do tego forum.',
                'not_owner' => 'Tylko autor posta może go edytować.',
                'topic_locked' => 'Nie możesz edytować postów w zamkniętym wątku.',
            ],

            'store' => [
                'play_more' => 'Zagraj w osu! przed rozpoczęciem pisania na forum! Jeżeli masz jakiś problem, utwórz nowy wątek w forum dot. pomocy.',
                'too_many_help_posts' => "Musisz zagrać w osu! przed utworzeniem kolejnych postów. Jeżeli nadal doświadczasz problemów, wyślij wiadomość na adres e-mail support@ppy.sh", // FIXME: unhardcode email address.
            ],
        ],

        'topic' => [
            'reply' => [
                'double_post' => 'Zedytuj swój poprzedni post zamiast tworzenia nowego.',
                'locked' => 'Nie możesz odpowiadać w zamkniętym wątku.',
                'no_forum_access' => 'Nie posiadasz dostępu do tego forum.',
                'no_permission' => 'Nie posiadasz uprawnień do odpowiadania.',

                'user' => [
                    'require_login' => 'Zaloguj się, aby odpowiedzieć.',
                    'restricted' => "Nie możesz odpowiadać podczas blokady konta.",
                    'silenced' => "Nie możesz odpowiadać po tym, jak twoje konto zostało uciszone.",
                ],
            ],

            'store' => [
                'no_forum_access' => 'Nie posiadasz dostępu do tego forum.',
                'no_permission' => 'Nie posiadasz uprawnień do utworzenia nowego wątku.',
                'forum_closed' => 'Forum zostało zamknięte i nie możesz w nim pisać.',
            ],

            'vote' => [
                'no_forum_access' => 'Nie posiadasz dostępu do tego forum.',
                'over' => 'Ankieta została zakończona i nie możesz już w niej głosować.',
                'play_more' => 'Nie grasz wystarczająco długo, aby głosować na forum.',
                'voted' => 'Nie możesz zmienić swojego głosu.',

                'user' => [
                    'require_login' => 'Zaloguj się, aby zagłosować.',
                    'restricted' => "Nie możesz głosować podczas blokady konta.",
                    'silenced' => "Nie możesz głosować po tym, jak twoje konto zostało uciszone.",
                ],
            ],

            'watch' => [
                'no_forum_access' => 'Nie posiadasz dostępu do tego forum.',
            ],
        ],

        'topic_cover' => [
            'edit' => [
                'uneditable' => 'Wybrano nieprawidłowe tło.',
                'not_owner' => 'Tylko autor może edytować tło.',
            ],
            'store' => [
                'forum_not_allowed' => 'Nie możesz ustawić tła wątku na tym forum.',
            ],
        ],

        'view' => [
            'admin_only' => 'Tylko administrator ma dostęp do tego forum.',
        ],
    ],

    'room' => [
        'destroy' => [
            'not_owner' => 'Tylko właściciel pokoju może go zamknąć.',
        ],
    ],

    'score' => [
        'pin' => [
            'disabled_type' => "Nie możesz przypiąć tego typu wyników",
            'failed' => "Nie możesz przypiąć wyniku zakończonego porażką.",
            'not_owner' => 'Nie możesz przypiąć czyjegoś wyniku.',
            'too_many' => 'Przypięto zbyt wiele wyników.',
        ],
    ],

    'team' => [
        'application' => [
            'store' => [
                'already_member' => "Jesteś już członkiem tego zespołu.",
                'already_other_member' => "Jesteś już członkiem innego zespołu.",
                'currently_applying' => 'Twoja prośba o dołączenie do drużyny oczekuje już na rozpatrzenie.',
                'team_closed' => 'Ten zespół nie przyjmuje aktualnie próśb o dołączenie.',
                'team_full' => "Ten zespół jest już pełny i nie może przyjąć więcej członków.",
            ],
        ],
        'part' => [
            'is_leader' => "Nie możesz opuścić zespołu jako jego lider.",
            'not_member' => 'Nie jesteś członkiem tego zespołu.',
        ],
        'store' => [
            'require_supporter_tag' => 'Wymagany jest osu!supporter, aby stworzyć drużynę.',
        ],
    ],

    'user' => [
        'page' => [
            'edit' => [
                'locked' => 'Strona użytkownika została zablokowana.',
                'not_owner' => 'Możesz edytować tylko własną stronę użytkownika.',
                'require_supporter_tag' => 'Aby to zrobić, wymagany jest status donatora osu!.',
            ],
        ],
        'update_email' => [
            'locked' => 'zmiana adresu e-mail jest zablokowana',
        ],
    ],
];
