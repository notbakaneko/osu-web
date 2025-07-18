<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'deleted' => '[verwijderde gebruiker]',

    'beatmapset_activities' => [
        'title' => ":user's modding geschiedenis",
        'title_compact' => 'Modding',

        'discussions' => [
            'title_recent' => 'Recent gestarte discussies',
        ],

        'events' => [
            'title_recent' => 'Recente gebeurtenissen',
        ],

        'posts' => [
            'title_recent' => 'Recente berichten',
        ],

        'votes_received' => [
            'title_most' => 'Meest geupvote door (laatste 3 maanden)',
        ],

        'votes_made' => [
            'title_most' => 'Meest geupvote (laatste 3 maanden)',
        ],
    ],

    'blocks' => [
        'banner_text' => 'U hebt deze gebruiker geblokkeerd.',
        'comment_text' => 'Deze reactie is verdekt.',
        'blocked_count' => 'geblokkeerde gebruikers (:count)',
        'hide_profile' => 'profiel verbergen',
        'hide_comment' => 'verbergen',
        'forum_post_text' => 'Deze post is verborgen.',
        'not_blocked' => 'Deze gebruiker is niet geblokkeerd.',
        'show_profile' => 'profiel weergeven',
        'show_comment' => 'tonen',
        'too_many' => 'Blok limiet bereikt.',
        'button' => [
            'block' => 'blokkeren',
            'unblock' => 'deblokkeren',
        ],
    ],

    'card' => [
        'gift_supporter' => 'Geef supporter tag cadeau',
        'loading' => 'Bezig met laden...',
        'send_message' => 'Bericht sturen',
    ],

    'create' => [
        'form' => [
            'password' => 'wachtwoord',
            'password_confirmation' => 'wacthwoordbevestiging',
            'submit' => 'account aanmaken',
            'user_email' => 'e-mail',
            'user_email_confirmation' => 'e-mailadres bevestigen',
            'username' => 'gebruikersnaam',

            'tos_notice' => [
                '_' => 'door een account aan te maken ga je akkoord met de :link',
                'link' => 'gebruiksvoorwaarden',
            ],
        ],
    ],

    'disabled' => [
        'title' => 'Uh-oh! Het lijkt erop dat je account is uitgeschakeld.',
        'warning' => "In het geval dat je een regel hebt overtreden, houd er rekening mee dat er over het algemeen een afkoelperiode van één maand is waarin we geen amnestieverzoeken in behandeling nemen. Na deze periode kunt u contact met ons opnemen als je dit nodig vind. Houd er rekening mee dat het maken van nieuwe accounts nadat uw account is uitgeschakeld, resulteert in een <strong> verlenging van deze cool-down van een maand </strong>. Houd er ook rekening mee dat voor <strong> elk account dat je maakt, je de regels verder overtreedt </strong>. We raden je ten zeerste aan om dit niet te doen!",

        'if_mistake' => [
            '_' => 'Als je denkt dat dit een vergissing is, ben je welkom om ons te contacteren (via :email of door te klikken op "? in de rechter onderhoek van deze pagina). Houd er rekening mee dat we altijd volledig vertrouwen hebben in onze acties, aangezien deze gebaseerd zijn op zeer solide gegevens. We behouden ons het recht voor om je verzoek te negeren als we het gevoel hebben dat je opzettelijk oneerlijk bent.',
            'email' => 'e-mail',
        ],

        'reasons' => [
            'compromised' => 'Uw account is beschadigd. Het kan tijdelijk worden uitgeschakeld terwijl de identiteit wordt bevestigd.',
            'opening' => 'Er zijn een aantal redenen die ertoe kunnen leiden dat uw account wordt uitgeschakeld:',

            'tos' => [
                '_' => 'Je hebt een of meer van onze :community_rules of :tos overtreden.',
                'community_rules' => 'community regels',
                'tos' => 'algemene voorwaarden',
            ],
        ],
    ],

    'filtering' => [
        'by_game_mode' => 'Leden per spelmodus',
    ],

    'force_reactivation' => [
        'reason' => [
            'inactive' => "",
            'inactive_different_country' => "Je account is lange tijd niet gebruikt.",
        ],
    ],

    'login' => [
        '_' => 'Inloggen',
        'button' => 'Inloggen',
        'button_posting' => 'Inloggen...',
        'email_login_disabled' => 'Inloggen met e-mail is momenteel uitgeschakeld. Gebruik in plaats daarvan de gebruikersnaam.',
        'failed' => 'Verkeerde login',
        'forgot' => 'Wachtwoord vergeten?',
        'info' => 'Log in om verder te gaan',
        'invalid_captcha' => 'Captcha ongeldig, vernieuw de pagina en probeer het opnieuw.',
        'locked_ip' => 'je IP adres is vergrendeld. Wacht enkele minuten.',
        'password' => 'Wachtwoord',
        'register' => "Heb je geen osu! account? Maak een nieuwe",
        'remember' => 'Onthoud deze computer',
        'title' => 'Log in om verder te gaan',
        'username' => 'Gebruikersnaam',

        'beta' => [
            'main' => 'Beta toegang is momenteel beperkt voor bepaalde gebruikers.',
            'small' => '(osu!supporters krijgen binnenkort in)',
        ],
    ],

    'ogp' => [
        'modding_description' => 'Beatmaps: :counts',
        'modding_description_empty' => 'Gebruiker heeft geen beatmaps...',

        'description' => [
            '_' => 'Rang (:ruleset): :global × :country',
            'country' => 'Land :rank',
            'global' => 'Globaal :rank',
        ],
    ],

    'posts' => [
        'title' => ':username\'s berichten',
    ],

    'anonymous' => [
        'login_link' => 'klik om in te loggen',
        'login_text' => 'log in',
        'username' => 'Gast',
        'error' => 'Je moet ingelogd zijn om dit te doen.',
    ],
    'logout_confirm' => 'Weet je zeker dat je wilt uitloggen? :(',
    'report' => [
        'button_text' => 'rapporteer',
        'comments' => 'Aanvullende opmerkingen',
        'placeholder' => 'Geef alstublieft informatie die je denkt te kunnen gebruiken.',
        'reason' => 'Reden',
        'thanks' => 'Bedankt voor je melding!',
        'title' => 'Rapporteer :username?',

        'actions' => [
            'send' => 'Stuur rapport',
            'cancel' => 'Annuleren',
        ],

        'options' => [
            'cheating' => 'Valsspelen',
            'inappropriate_chat' => '',
            'insults' => 'Beledigen van mij / anderen',
            'multiple_accounts' => 'Meerdere accounts gebruiken',
            'nonsense' => 'Nonsense',
            'other' => 'Anders (type hieronder)',
            'spam' => 'Spammen',
            'unwanted_content' => 'Linken van ongepaste inhoud',
        ],
    ],
    'restricted_banner' => [
        'title' => 'Je account is gerestrict!',
        'message' => 'Zolang je gerestrict bent, kun je niet communiceren met andere spelers en zijn je scores enkel voor jou zichtbaar. Meestal gebeurt deze restrictie automatisch en wordt ze opgeheven binnen 24 uur. :link',
        'message_link' => 'Check deze pagina voor meer informatie.',
    ],
    'show' => [
        'age' => ':age jaar oud',
        'change_avatar' => 'verander je avatar!',
        'first_members' => 'Hier sinds het begin',
        'is_developer' => 'osu!developer',
        'is_supporter' => 'osu!supporter',
        'joined_at' => 'Werd lid op :date',
        'lastvisit' => 'Laatst gezien op :date',
        'lastvisit_online' => 'Nu online',
        'missingtext' => 'Je hebt misschien een typfout gemaakt! (of de gebruiker is verbannen)',
        'origin_country' => 'Uit :country',
        'previous_usernames' => 'vroeger bekend als',
        'plays_with' => 'Speelt met :devices',

        'comments_count' => [
            '_' => 'Heeft :link gepost',
            'count' => ':count_delimited reactie|:count_delimited reacties',
        ],
        'cover' => [
            'to_0' => 'Omslagfoto verbergen',
            'to_1' => 'Omslagfoto tonen',
        ],
        'daily_challenge' => [
            'daily' => '',
            'daily_streak_best' => '',
            'daily_streak_current' => '',
            'playcount' => '',
            'title' => '',
            'top_10p_placements' => '',
            'top_50p_placements' => '',
            'weekly' => '',
            'weekly_streak_best' => '',
            'weekly_streak_current' => '',

            'unit' => [
                'day' => '',
                'week' => '',
            ],
        ],
        'edit' => [
            'cover' => [
                'button' => 'Verander Profiel Cover',
                'defaults_info' => 'In de toekomst zullen er meer cover opties beschikbaar zijn',
                'holdover_remove_confirm' => "",
                'title' => '',

                'upload' => [
                    'broken_file' => 'Afbeelding verwerken mislukt. Controleer de geüploade afbeelding en probeer opnieuw.',
                    'button' => 'Upload afbeelding',
                    'dropzone' => 'Drop hier om te uploaden',
                    'dropzone_info' => 'Je kunt je afbeelding ook hier droppen om te uploaden',
                    'size_info' => 'Cover grootte moet 2400x620 zijn',
                    'too_large' => 'Het geüploade bestand is te groot.',
                    'unsupported_format' => 'Niet ondersteund formaat.',

                    'restriction_info' => [
                        '_' => 'Upload beschikbaar alleen voor :link',
                        'link' => 'osu!supporters',
                    ],
                ],
            ],

            'default_playmode' => [
                'is_default_tooltip' => 'standaard spelmode',
                'set' => 'zet :mode als standaard profiel spelmode',
            ],

            'hue' => [
                'reset_no_supporter' => '',
                'title' => '',

                'supporter' => [
                    '_' => '',
                    'link' => '',
                ],
            ],
        ],

        'extra' => [
            'none' => 'geen',
            'unranked' => 'Geen recente plays',

            'achievements' => [
                'achieved-on' => 'Behaald op :date',
                'locked' => 'Vergrendeld',
                'title' => 'Prestaties',
            ],
            'beatmaps' => [
                'by_artist' => 'door :artist',
                'title' => 'Beatmaps',

                'favourite' => [
                    'title' => 'Favoriete Beatmaps',
                ],
                'graveyard' => [
                    'title' => 'Beatmaps in het kerkhof',
                ],
                'guest' => [
                    'title' => 'Beatmaps met gastdeelname',
                ],
                'loved' => [
                    'title' => 'Loved Beatmaps',
                ],
                'nominated' => [
                    'title' => 'Genomineerde Ranked Beatmaps',
                ],
                'pending' => [
                    'title' => 'Afwachtende Beatmaps',
                ],
                'ranked' => [
                    'title' => 'Gerankte & Goedgekeurde Beatmaps',
                ],
            ],
            'discussions' => [
                'title' => 'Discussies',
                'title_longer' => 'Recente discussies',
                'show_more' => 'zie meer discussies',
            ],
            'events' => [
                'title' => 'Gebeurtenissen',
                'title_longer' => 'Recente gebeurtenissen',
                'show_more' => 'meer gebeurtenissen zien',
            ],
            'historical' => [
                'title' => 'Historisch',

                'monthly_playcounts' => [
                    'title' => 'Speelgeschiedenis',
                    'count_label' => 'Aantal keer gespeeld',
                ],
                'most_played' => [
                    'count' => 'keer gespeeld',
                    'title' => 'Meest Gespeelde Beatmaps',
                ],
                'recent_plays' => [
                    'accuracy' => 'precisie: :percentage',
                    'title' => 'Recent gespeeld',
                ],
                'replays_watched_counts' => [
                    'title' => 'Kijkgeschiedenis Replays',
                    'count_label' => 'Aantal Replays Bekeken',
                ],
            ],
            'kudosu' => [
                'recent_entries' => 'Recente Kudosu Geschiedenis',
                'title' => 'Kudosu!',
                'total' => 'Totaal Aantal Kudosu Verdiend',

                'entry' => [
                    'amount' => ':amount kudosu',
                    'empty' => "Deze gebruiker heeft nog geen kudosu ontvangen!",

                    'beatmap_discussion' => [
                        'allow_kudosu' => [
                            'give' => 'Kreeg :amount kudosu voor in beroep gaan van afwijzing in modding post :post',
                        ],

                        'deny_kudosu' => [
                            'reset' => ':amount ontkend voor modding bericht :post',
                        ],

                        'delete' => [
                            'reset' => ':amount verloren vanwege verwijdering modding post :post',
                        ],

                        'restore' => [
                            'give' => 'Kreeg :amount voor modding post :post the herstellen',
                        ],

                        'vote' => [
                            'give' => 'Kreeg :amount via stemmen in modding post :post',
                            'reset' => 'Verloor :amount uit verliezende stemmen van post :post',
                        ],

                        'recalculate' => [
                            'give' => 'Kreeg :post uit stemming hertelling in modding post van :post',
                            'reset' => 'Verloor :post uit stemming hertelling in modding post van :post',
                        ],
                    ],

                    'forum_post' => [
                        'give' => ':amount ontvangen van :giver voor :post',
                        'reset' => 'Kudosu reset door :giver voor de post :post',
                        'revoke' => 'Kudosu geweigerd door :giver voor :post',
                    ],
                ],

                'total_info' => [
                    '_' => 'Op basis van hoeveel bijdrage de gebruiker heeft geleverd aan beatmap moderatie. Zie :link voor meer informatie.',
                    'link' => 'deze pagina',
                ],
            ],
            'me' => [
                'title' => 'ik!',
            ],
            'medals' => [
                'empty' => "Deze gebruiker heeft er nog geen ;_;",
                'recent' => 'Recent',
                'title' => 'Medailles',
            ],
            'playlists' => [
                'title' => 'Spellen via afspeellijst',
            ],
            'posts' => [
                'title' => 'Berichten',
                'title_longer' => 'Recente berichten',
                'show_more' => 'bekijk meer berichten',
            ],
            'recent_activity' => [
                'title' => 'Recent',
            ],
            'realtime' => [
                'title' => 'Multiplayerspellen',
            ],
            'top_ranks' => [
                'download_replay' => 'Replay Downloaden',
                'not_ranked' => 'Enkel gerankte beatmaps geven pp.',
                'pp_weight' => 'gewogen :percentage',
                'view_details' => 'Details Bekijken',
                'title' => 'Ranks',

                'best' => [
                    'title' => 'Beste Prestatie',
                ],
                'first' => [
                    'title' => 'Eerste Ranks',
                ],
                'pin' => [
                    'to_0' => 'Losmaken',
                    'to_0_done' => 'Losgemaakte score',
                    'to_1' => 'Vastzetten',
                    'to_1_done' => 'Vastgezette score',
                ],
                'pinned' => [
                    'title' => 'Vastgezette Scores',
                ],
            ],
            'votes' => [
                'given' => 'Gegeven stemmen (de afgelopen 3 maanden)',
                'received' => 'Ontvangen stemmen (laatste 3 maanden)',
                'title' => 'Stemmen',
                'title_longer' => 'Recente stemmen',
                'vote_count' => ':count_delimited stemmen|:count_delimited stemmen',
            ],
            'account_standing' => [
                'title' => 'Account Reputatie',
                'bad_standing' => "<strong>:username's</strong> account heeft geen goede reputatie :(",
                'remaining_silence' => '<strong>:username</strong> kan terug spreken in :duration.',

                'recent_infringements' => [
                    'title' => 'Recente Overtredingen',
                    'date' => 'datum',
                    'action' => 'actie',
                    'length' => 'lengte',
                    'length_indefinite' => 'Onbepaald',
                    'description' => 'omschrijving',
                    'actor' => 'door :username',

                    'actions' => [
                        'restriction' => 'Ban',
                        'silence' => 'Silence',
                        'tournament_ban' => 'Verbannen van toernooien',
                        'note' => 'Opmerking',
                    ],
                ],
            ],
        ],

        'info' => [
            'discord' => '',
            'interests' => 'Interesses',
            'location' => 'Huidige Locatie',
            'occupation' => 'Beroep',
            'twitter' => '',
            'website' => 'Website',
        ],
        'not_found' => [
            'reason_1' => 'Misschien is hun gebruikersnaam veranderd.',
            'reason_2' => 'Het account kan tijdelijk onbeschikbaar zijn vanwege beveiligingsredenen of misbruik.',
            'reason_3' => 'Misschien heb je een typefout gemaakt!',
            'reason_header' => 'Er enkele mogelijke redenen hiervoor:',
            'title' => 'Gebruiker niet gevonden! ;_;',
        ],
        'page' => [
            'button' => 'Profielpagina bewerken',
            'description' => '<strong>ik!</strong> is een persoonlijk bewerkbaar gedeelte van je profiel.',
            'edit_big' => 'Bewerk me!',
            'placeholder' => 'Typ pagina inhoud hier',

            'restriction_info' => [
                '_' => 'U moet een :link zijn om deze functie te ontgrendelen.',
                'link' => 'osu!supporter',
            ],
        ],
        'post_count' => [
            '_' => 'Plaatste :link',
            'count' => ':count forum bericht|:count forum berichten',
        ],
        'rank' => [
            'country' => 'Landelijke rank voor :mode',
            'country_simple' => 'Landelijke Ranking',
            'global' => 'Globale rank voor :mode',
            'global_simple' => 'Wereldwijde Ranking',
            'highest' => 'Hoogste rank: :rank op :date',
        ],
        'season_stats' => [
            'division_top_percentage' => '',
            'total_score' => '',
        ],
        'stats' => [
            'hit_accuracy' => 'Hit Precisie',
            'hits_per_play' => '',
            'level' => 'Level :level',
            'level_progress' => 'Voortgang naar volgend level',
            'maximum_combo' => 'Hoogste Combo',
            'medals' => 'Medailles',
            'play_count' => 'Play Count',
            'play_time' => 'Totale Speeltijd',
            'ranked_score' => 'Gerankte Score',
            'replays_watched_by_others' => 'Replays Bekeken door Anderen',
            'score_ranks' => 'Score Ranks',
            'total_hits' => 'Totaal Aantal Hits',
            'total_score' => 'Totale Score',
            // modding stats
            'graveyard_beatmapset_count' => 'Graveyarded Beatmaps',
            'loved_beatmapset_count' => 'Loved Beatmaps',
            'pending_beatmapset_count' => 'Afwachtende Beatmaps',
            'ranked_beatmapset_count' => 'Gerankte & Goedgekeurde Beatmaps',
        ],
    ],

    'silenced_banner' => [
        'title' => 'Je bent momenteel gedempt.',
        'message' => 'Sommige acties zijn mogelijk niet beschikbaar.',
    ],

    'status' => [
        'all' => 'Alle',
        'online' => 'Online',
        'offline' => 'Offline',
    ],
    'store' => [
        'from_client' => 'registreer je alsjeblieft via de game client!',
        'from_web' => 'voltooi alsjeblieft de registratie via de osu! website',
        'saved' => 'Door gebruiker gemaakt',
    ],
    'verify' => [
        'title' => 'Accountverificatie',
    ],

    'view_mode' => [
        'brick' => 'Baksteen weergave',
        'card' => 'Kaartweergave',
        'list' => 'Lijst weergave',
    ],
];
