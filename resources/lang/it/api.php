<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'error' => [
        'chat' => [
            'empty' => 'Non puoi inviare messaggi vuoti.',
            'limit_exceeded' => 'Stai inviando messaggi troppo velocemente, aspetta un po\' prima di riprovare.',
            'too_long' => 'Il messaggio che vuoi inviare è troppo lungo.',
        ],
    ],

    'scopes' => [
        'bot' => 'Comportarsi come bot in chat.',
        'identify' => 'Identificarti e leggere il tuo profilo pubblico.',

        'chat' => [
            'read' => 'Leggere messaggi a nome tuo.',
            'write' => 'Mandare messaggi a nome tuo.',
            'write_manage' => 'Unirsi e lasciare canali a nome tuo.',
        ],

        'forum' => [
            'write' => 'Creare e modificare i topic e i post del forum a nome tuo.',
        ],

        'friends' => [
            'read' => 'Vedere chi stai seguendo.',
        ],

        'public' => 'Leggere dati pubblici a nome tuo.',
    ],
];
