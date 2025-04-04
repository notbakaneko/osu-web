<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'invalid_ruleset' => '',

    'change_owner' => [
        'too_many' => '',
    ],

    'discussion-votes' => [
        'update' => [
            'error' => 'Hiba a szavazat frissítése közben',
        ],
    ],

    'discussions' => [
        'allow_kudosu' => 'kudosu engedélyezése',
        'beatmap_information' => 'Beatmap Oldal',
        'delete' => 'törlés',
        'deleted' => 'Eltávolítva :editor által, :delete_time-kor.',
        'deny_kudosu' => 'kudosu megtagadása',
        'edit' => 'szerkesztés',
        'edited' => 'Utoljára frissítve :editor által, :update_time-kor.',
        'guest' => 'Vendég nehézséget készítette: :user',
        'kudosu_denied' => 'Kudosu szerzéstől megtagadva.',
        'message_placeholder_deleted_beatmap' => 'Ez a nehézség törölve lett, ezért a beszélgetés nem lehetséges.',
        'message_placeholder_locked' => 'A beatmap megbeszélése meg lett tiltva.',
        'message_placeholder_silenced' => "Nem hozhatsz létre beszélgetést, amíg némítva vagy.",
        'message_type_select' => 'Komment-típus választása',
        'reply_notice' => 'Nyomj entert a válaszoláshoz.',
        'reply_resolve_notice' => 'Nyomj entert a válaszhoz. Nyomj ctrl+entert a válaszhoz és a megoldáshoz.',
        'reply_placeholder' => 'Ide írd a válaszod',
        'require-login' => 'Kérlek jelentkezz be a hozzászóláshoz illetve válaszoláshoz',
        'resolved' => 'Megoldott',
        'restore' => 'visszaállítás',
        'show_deleted' => 'Töröltek megjelenítése',
        'title' => 'Megbeszélések',
        'unresolved_count' => ':count_delimited megoldatlan probléma|:count_delimited megoldatlan probléma',

        'collapse' => [
            'all-collapse' => 'Az összes becsukása',
            'all-expand' => 'Az összes kinyitása',
        ],

        'empty' => [
            'empty' => 'Egyetlen megbeszélés sincs még!',
            'hidden' => 'Egyetlen megbeszélés sem egyezik a kijelölt szűrővel.',
        ],

        'lock' => [
            'button' => [
                'lock' => 'Megbeszélés zárolása',
                'unlock' => 'Megbeszélés megnyitása',
            ],

            'prompt' => [
                'lock' => 'Zárolás oka',
                'unlock' => 'Biztos ki akarod nyitni?',
            ],
        ],

        'message_hint' => [
            'in_general' => 'Ez a hozzászólás be fog kerülni az általános beatmap megbeszélésébe. A beatmap modolásához időbélyeggel kezdd az üzenetet (pl.: 00:12:345).',
            'in_timeline' => 'Több időbélyeg modolásához több poszt szükséges (egy poszt egy időbélyeghez).',
        ],

        'message_placeholder' => [
            'general' => 'Írj ide az Általános (:version) részlegbe való posztoláshoz',
            'generalAll' => 'Írj ide az Általános (Minden nehézség) való hozzászóláshoz',
            'review' => 'Írj ide az Összetett hozzászóláshoz',
            'timeline' => 'Írj ide az Idővonalra (:version) való hozzászóláshoz',
        ],

        'message_type' => [
            'disqualify' => 'Diszkvalifikálás',
            'hype' => 'Hype!',
            'mapper_note' => 'Megjegyzés',
            'nomination_reset' => 'Nominálás Visszaállítása',
            'praise' => 'Dicséret',
            'problem' => 'Probléma',
            'problem_warning' => 'Probléma jelentése',
            'review' => 'Összegzés',
            'suggestion' => 'Javaslat',
        ],

        'message_type_title' => [
            'disqualify' => 'Diszkvalifikáció posztolása',
            'hype' => 'Hype! posztolása',
            'mapper_note' => 'Jegyzet posztolása',
            'nomination_reset' => 'Összes nominálás eltávolítása',
            'praise' => 'Dicséret posztolása',
            'problem' => 'Probléma posztolása',
            'problem_warning' => 'Probléma posztolása',
            'review' => 'Vélemény posztolása',
            'suggestion' => 'Javaslat posztolása',
        ],

        'mode' => [
            'events' => 'Előzmények',
            'general' => 'Általános :scope',
            'reviews' => 'Összegzések',
            'timeline' => 'Idővonal',
            'scopes' => [
                'general' => 'Ez a nehézség',
                'generalAll' => 'Minden nehézség',
            ],
        ],

        'new' => [
            'pin' => 'Rögzítés',
            'timestamp' => 'Időbélyeg',
            'timestamp_missing' => 'Időbélyeg hozzáadásához nyomj ctrl-c billentyűkombinációt szerkesztő módban, majd illeszd be az üzenetedbe!',
            'title' => 'Új beszélgetés indítása',
            'unpin' => 'Rögzítés feloldása',
        ],

        'review' => [
            'new' => 'Új Összetett hozzászólás',
            'embed' => [
                'delete' => 'Törlés',
                'missing' => 'Hozzászólás törölve',
                'unlink' => 'Leválasztás',
                'unsaved' => 'Mentetlen',
                'timestamp' => [
                    'all-diff' => 'A "Minden nehézség" alatti hozzászólásokat nem lehet időbélyegezni.',
                    'diff' => 'Ha :type típussal fog kezdődni, akkor az idővonal alatt fog megjelenni.',
                ],
            ],
            'insert-block' => [
                'paragraph' => 'Bekezdés beszúrása',
                'praise' => 'Dícséret beszúrása',
                'problem' => 'Probléma beszúrása',
                'suggestion' => 'Javaslat beszúrása',
            ],
        ],

        'show' => [
            'title' => ':title készítette :mapper',
        ],

        'sort' => [
            'created_at' => 'Létrehozás ideje',
            'timeline' => 'Idővonal',
            'updated_at' => 'Utolsó frissítés',
        ],

        'stats' => [
            'deleted' => 'Törölve',
            'mapper_notes' => 'Megjegyzések',
            'mine' => 'Saját',
            'pending' => 'Függő',
            'praises' => 'Dicséretek',
            'resolved' => 'Megoldott',
            'total' => 'Mind',
        ],

        'status-messages' => [
            'approved' => 'Beatmap jóváhagyásának ideje: :date!',
            'graveyard' => "A beatmap :date óta nem kapott frissítést, valószínűleg el lett hanyagolva a készítő által...",
            'loved' => 'Ez a beatmap :date-kor hozzá lett adva a szeretettek közé!',
            'ranked' => 'Ez a beatmap :date-kor lett rangsorolt!',
            'wip' => 'Megjegyzés: Ez a beatmap még készítés alatt áll.',
        ],

        'votes' => [
            'none' => [
                'down' => 'Nincsenek leértékelések',
                'up' => 'Nincsenek felértékelések',
            ],
            'latest' => [
                'down' => 'Legutóbbi leértékelések',
                'up' => 'Legutóbbi felértékelések',
            ],
        ],
    ],

    'hype' => [
        'button' => 'Beatmap Hype-olása!',
        'button_done' => 'Már Hype-olt!',
        'confirm' => "Biztos vagy benne? Ezzel elhasználsz egyet a(z) :n darab hype-odból, ami visszavonhatatlan.",
        'explanation' => 'Hype-old a beatmap-et, hogy láthatóbbá tedd a jelöléshez és a rangsoroláshoz!',
        'explanation_guest' => 'Jelentkezz be és hype-old a beatmap-et, hogy láthatóbbá tedd a jelöléshez és a rangsoroláshoz!',
        'new_time' => "Kapsz még egy hype-ot :new_time-kor.",
        'remaining' => 'Még :remaining darab hype-od maradt.',
        'required_text' => 'Hype: :current/:required',
        'section_title' => 'Hype Vonat',
        'title' => 'Hype',
    ],

    'feedback' => [
        'button' => 'Visszajelzés Küldése',
    ],

    'nominations' => [
        'already_nominated' => 'Már nomináltad ezt a beatmap-et.',
        'cannot_nominate' => 'Nem nominálhatod ezt a beatmap-et ebben a játékmódban.',
        'delete' => 'Törlés',
        'delete_own_confirm' => 'Biztos vagy benne? A beatmap törlésre kerül és vissza leszel irányítva a profilodra.',
        'delete_other_confirm' => 'Biztos vagy benne? A beatmap törlésre kerül és vissza leszel irányítva a felhasználó profiljára.',
        'disqualification_prompt' => 'Diszkvalifikáció oka?',
        'disqualified_at' => 'Diszkvalifikálva :time_ago (:reason).',
        'disqualified_no_reason' => 'nincs indok meghatározva',
        'disqualify' => 'Diszkvalifikálás',
        'incorrect_state' => 'Hiba a művelet végrehajtása közben, próbáld meg újratölteni az oldalt.',
        'love' => 'Love',
        'love_choose' => 'Nehézség választása a szeretettnek',
        'love_confirm' => 'Love-olod ezt a beatmap-et?',
        'nominate' => 'Nominálás',
        'nominate_confirm' => 'Nominálod ezt a beatmapot?',
        'nominated_by' => 'nominálva :users által',
        'not_enough_hype' => "Nincs elég Hype.",
        'remove_from_loved' => 'Eltávolítás a Loved közül',
        'remove_from_loved_prompt' => 'Loved-ból való eltávolítás indoka:',
        'required_text' => 'Nominálások: :current/:required',
        'reset_message_deleted' => 'törölve',
        'title' => 'Nominálási Állapot',
        'unresolved_issues' => 'Még mindig vannak megoldatlan problémák amelyeket először kezelni kell.',

        'rank_estimate' => [
            '_' => 'Ez a pálya rangsorolt lesz :date, ha nem merülnek fel további problémák. Jelenleg a #:position helyen áll: :queue.',
            'unresolved_problems' => 'Ez a map jelenleg blokkolva van a Kvalifált állapot elhagyásából ameddig :problems meg nem vannak oldva.',
            'problems' => 'ezek a problémák',
            'on' => ':date',
            'queue' => 'ranglistázási sor',
            'soon' => 'hamarosan',
        ],

        'reset_at' => [
            'nomination_reset' => ':user újraindította a Nominálási folyamatot :time_ago: :discussion (:message).',
            'disqualify' => ':time_ago óta diszkvalifikálva :user által egy új probléma miatt :discussion (:message).',
        ],

        'reset_confirm' => [
            'disqualify' => 'Biztos vagy benne? Ezzel kizárod a beatmap-et a kvalifikálásból és alaphelyzetbe áll a nominálás.',
            'nomination_reset' => 'Biztos vagy ebben? Egy új probléma posztolása alaphelyzetbe állítja a nominálási folyamatot.',
            'problem_warning' => 'Biztos, hogy hibát szeretnél jelenteni ezen a beatmap-en? Ez jelezni fog a Beatmap Nominálóknak.',
        ],
    ],

    'listing' => [
        'search' => [
            'prompt' => 'írj kulcsszavakat...',
            'login_required' => 'Jelentkezz be a kereséshez.',
            'options' => 'További Keresési Beállítások',
            'supporter_filter' => ':filters általi szűrés aktív osu!supporter cím-et igényel',
            'not-found' => 'nincs találat',
            'not-found-quote' => '... nope, semmit sem találtam.',
            'filters' => [
                'extra' => 'Extra',
                'general' => 'Általános',
                'genre' => 'Műfaj',
                'language' => 'Nyelv',
                'mode' => 'Mód',
                'nsfw' => 'Felnőtt tartalom',
                'played' => 'Lejátszott',
                'rank' => 'Elért Rang',
                'status' => 'Kategóriák',
            ],
            'sorting' => [
                'title' => 'Cím',
                'artist' => 'Előadó',
                'difficulty' => 'Nehézség',
                'favourites' => 'Kedvencek',
                'updated' => 'Frissítve',
                'ranked' => 'Rangsorolt',
                'rating' => 'Értékelés',
                'plays' => 'Játszások',
                'relevance' => 'Relevancia',
                'nominations' => 'Nominációk',
            ],
            'supporter_filter_quote' => [
                '_' => ':filters általi szűrés aktív :link-et igényel',
                'link_text' => 'osu!supporter cím',
            ],
        ],
    ],
    'general' => [
        'converts' => 'Konvertált beatmap-ek tartalmazása',
        'featured_artists' => 'Kiemelt előadók',
        'follows' => 'Követett Mapperek',
        'recommended' => 'Ajánlott nehézség',
        'spotlights' => 'Reflektorfényezett beatmapek',
    ],
    'mode' => [
        'all' => 'Összes',
        'any' => 'Bármelyik',
        'osu' => '',
        'taiko' => '',
        'fruits' => '',
        'mania' => '',
        'undefined' => 'nincs beállítva',
    ],
    'status' => [
        'any' => 'Bármelyik',
        'approved' => 'Jóváhagyott',
        'favourites' => 'Kedvencek',
        'graveyard' => 'Temető',
        'leaderboard' => 'Ranglistás',
        'loved' => 'Loved',
        'mine' => 'Saját Mapjaim',
        'pending' => 'Függőben lévő & WIP',
        'wip' => 'Készítés alatt',
        'qualified' => 'Kvalifikált',
        'ranked' => 'Rangsorolt',
    ],
    'genre' => [
        'any' => 'Bármelyik',
        'unspecified' => 'Nem meghatározott',
        'video-game' => 'Videojáték',
        'anime' => 'Anime',
        'rock' => 'Rock',
        'pop' => 'Pop',
        'other' => 'Egyéb',
        'novelty' => 'Kortárs',
        'hip-hop' => 'Hip Hop',
        'electronic' => 'Elektronikus',
        'metal' => 'Metál',
        'classical' => 'Klasszikus',
        'folk' => 'Népi',
        'jazz' => 'Jazz',
    ],
    'language' => [
        'any' => 'Összes',
        'english' => 'Angol',
        'chinese' => 'Kínai',
        'french' => 'Francia',
        'german' => 'Német',
        'italian' => 'Olasz',
        'japanese' => 'Japán',
        'korean' => 'Koreai',
        'spanish' => 'Spanyol',
        'swedish' => 'Svéd',
        'russian' => 'Orosz',
        'polish' => 'Lengyel',
        'instrumental' => 'Instrumentális',
        'other' => 'Egyéb',
        'unspecified' => 'Meghatározatlan',
    ],

    'nsfw' => [
        'exclude' => 'Elrejtés',
        'include' => 'Mutatás',
    ],

    'played' => [
        'any' => 'Bármelyik',
        'played' => 'LeJátszott',
        'unplayed' => 'Nem játszott',
    ],
    'extra' => [
        'video' => 'Videót Tartalmaz',
        'storyboard' => 'Storyboard-ot Tartalmaz',
    ],
    'rank' => [
        'any' => 'Bármelyik',
        'XH' => 'Ezüst SS',
        'X' => '',
        'SH' => 'Ezüst S',
        'S' => '',
        'A' => '',
        'B' => '',
        'C' => '',
        'D' => '',
    ],
    'panel' => [
        'playcount' => 'Játékszám: :count',
        'favourites' => 'Kedvencek: :count',
    ],
    'variant' => [
        'mania' => [
            '4k' => '4K',
            '7k' => '7K',
            'all' => 'Összes',
        ],
    ],
];
