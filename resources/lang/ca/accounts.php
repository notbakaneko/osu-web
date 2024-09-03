<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'edit' => [
        'title_compact' => 'configuració del compte',
        'username' => 'nom d’usuari',

        'avatar' => [
            'title' => 'Avatar',
            'reset' => '',
            'rules' => 'Si us plau asseguri\'s que el seu avatar s\'adhereix a :link. <br/> Això vol dir que ha de ser <strong>adequat per a totes les edats</strong>. És a dir, sense nuesa, blasfèmia o contingut suggestiu.',
            'rules_link' => 'regles de la comunitat',
        ],

        'email' => [
            'new' => 'nou correu electrònic',
            'new_confirmation' => 'confirmació per correu electrònic',
            'title' => 'Correu electrònic',
            'locked' => [
                '_' => 'Si us plau, contacta amb el :accounts si necessites que s\'actualitzi el teu correu electrònic.',
                'accounts' => 'equip de suport de comptes',
            ],
        ],

        'legacy_api' => [
            'api' => 'api',
            'irc' => 'irc',
            'title' => 'API heretada',
        ],

        'password' => [
            'current' => 'contrasenya actual',
            'new' => 'nova contrasenya',
            'new_confirmation' => 'confirmació de contrasenya',
            'title' => 'Contrasenya',
        ],

        'profile' => [
            'country' => 'país',
            'title' => 'Perfil',

            'country_change' => [
                '_' => "Sembla que el país del teu compte no coincideix amb el teu país de residència. :update_link.",
                'update_link' => 'Actualitza a :country',
            ],

            'user' => [
                'user_discord' => '',
                'user_from' => 'ubicació actual',
                'user_interests' => 'interessos',
                'user_occ' => 'ocupació',
                'user_twitter' => '',
                'user_website' => 'lloc web',
            ],
        ],

        'signature' => [
            'title' => 'Signatura',
            'update' => 'actualitzar',
        ],
    ],

    'github_user' => [
        'info' => "Si ets un col·laborador dels repositoris de codi obert d'osu!, enllaçant el teu compte de GitHub aquí, s'associarà les entrades del registre de canvis amb el teu perfil d'osu!. Comptes de GitHub sense historial de contribucions a osu! no es poden enllaçar.",
        'link' => 'Enllaça el compte de GitHub',
        'title' => 'GitHub',
        'unlink' => 'Desenllaça el compte de GitHub',

        'error' => [
            'already_linked' => 'Aquest compte de GitHub ja està enllaçat a un usuari diferent.',
            'no_contribution' => 'No es pot enllaçar el compte de GitHub sense cap historial de contribucions als repositoris d\'osu!',
            'unverified_email' => 'Verifica el teu correu electrònic principal a GitHub i torna a provar d\'enllaçar el teu compte.',
        ],
    ],

    'notifications' => [
        'beatmapset_discussion_qualified_problem' => 'rebre notificacions de nous problemes en beatmaps qualificats dels següents modes',
        'beatmapset_disqualify' => 'rebre notificacions per quan els beatmaps dels següents modes siguin desqualificats',
        'comment_reply' => 'rebre notificacions de respostes als teus comentaris',
        'title' => 'Notificacions',
        'topic_auto_subscribe' => 'habilita automàticament les notificacions en els nous temes de fòrum que creeu',

        'options' => [
            '_' => 'opcions d\'entrega',
            'beatmap_owner_change' => 'dificultat de convidat',
            'beatmapset:modding' => 'modding de beatmaps',
            'channel_message' => 'missatges de xat privats',
            'comment_new' => 'comentaris nous',
            'forum_topic_reply' => 'resposta del tema',
            'mail' => 'correu',
            'mapping' => 'creador de beatmaps',
            'push' => 'push',
        ],
    ],

    'oauth' => [
        'authorized_clients' => 'clients autoritzats',
        'own_clients' => 'clients propis',
        'title' => 'OAuth',
    ],

    'options' => [
        'beatmapset_show_nsfw' => 'amagar advertiments per a contingut explícit en beatmaps',
        'beatmapset_title_show_original' => 'mostra les metadades del beatmap en l\'idioma original',
        'title' => 'Opcions',

        'beatmapset_download' => [
            '_' => 'tipus de baixada de beatmap predeterminat',
            'all' => 'amb vídeo si està disponible',
            'direct' => 'obrir a osu!direct',
            'no_video' => 'sense vídeo',
        ],
    ],

    'playstyles' => [
        'keyboard' => 'teclat',
        'mouse' => 'ratolí',
        'tablet' => 'tauleta',
        'title' => 'Estils de joc',
        'touch' => 'tocar',
    ],

    'privacy' => [
        'friends_only' => 'bloquejar els missatges privats de persones que no són a la llista d\'amics',
        'hide_online' => 'amaga la teva presència en línia',
        'title' => 'Privadesa',
    ],

    'security' => [
        'current_session' => 'actual',
        'end_session' => 'Finalitzar sessió',
        'end_session_confirmation' => 'Això finalitzarà immediatament la sessió en aquest dispositiu. Estàs segur?',
        'last_active' => 'Última connexió:',
        'title' => 'Seguretat',
        'web_sessions' => 'sessions web',
    ],

    'update_email' => [
        'update' => 'actualitzar',
    ],

    'update_password' => [
        'update' => 'actualitzar',
    ],

    'verification_completed' => [
        'text' => 'Ja pots tancar aquesta pestanya/finestra',
        'title' => 'Verificació completada',
    ],

    'verification_invalid' => [
        'title' => 'Enllaç de verificació no vàlid o caducat',
    ],
];
