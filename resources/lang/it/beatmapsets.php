<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'availability' => [
        'disabled' => 'Questa beatmap non è al momento disponibile per il download.',
        'parts-removed' => 'Porzioni di questa beatmap sono state rimosse su richiesta del creatore o di un titolare di copyright di terze parti.',
        'more-info' => 'Controlla qui per maggiori dettagli.',
        'rule_violation' => 'Alcuni elementi contenuti in questa mappa sono stati rimossi dopo che sono stati giudicati non idonei per l\'uso in osu!.',
    ],

    'cover' => [
        'deleted' => 'Beatmap eliminata',
    ],

    'download' => [
        'limit_exceeded' => 'Rallenta, gioca di più.',
        'no_mirrors' => 'Nessun server di download disponibile.',
    ],

    'featured_artist_badge' => [
        'label' => 'Artista in primo piano',
    ],

    'index' => [
        'title' => 'Lista Beatmap',
        'guest_title' => 'Beatmap',
    ],

    'panel' => [
        'empty' => 'nessuna beatmap',

        'download' => [
            'all' => 'scarica',
            'video' => 'scarica con il video',
            'no_video' => 'scarica senza il video',
            'direct' => 'apri in osu!direct',
        ],
    ],

    'nominate' => [
        'bng_limited_too_many_rulesets' => 'I proponenti in prova non possono nominare più set di regole.',
        'full_nomination_required' => 'Devi essere un proponente a pieno titolo per effettuare la nomina definitiva di un set di regole.',
        'hybrid_requires_modes' => 'Un beatmapset ibrido richiede che venga selezionata almeno una modalità di gioco per poterla nominare.',
        'incorrect_mode' => 'Non hai il permesso di nominare per la modalità: :mode',
        'invalid_limited_nomination' => 'Questa beatmap contiene nomine non valide e non può essere qualificata in questo stato.',
        'invalid_ruleset' => 'Questa nomina contiene modalità non valide.',
        'too_many' => 'Requisito di nomina già soddisfatto.',
        'too_many_non_main_ruleset' => 'Requisiti di nomina già soddisfatti per le modalità non principali.',

        'dialog' => [
            'confirmation' => 'Sei sicuro di voler nominare questa beatmap?',
            'different_nominator_warning' => 'Qualificare questa beatmap con diversi nominatori resetterà la sua posizione nella coda di qualifica.',
            'header' => 'Nomina Beatmap',
            'hybrid_warning' => 'nota: puoi nominare una sola volta, quindi assicurati di nominare per tutte le modalità di gioco che vuoi',
            'current_main_ruleset' => 'La modalità principale al momento è: :ruleset',
            'which_modes' => 'Nominare per quali modalità?',
        ],
    ],

    'nsfw_badge' => [
        'label' => 'Explicit',
    ],

    'show' => [
        'discussion' => 'Discussione',

        'admin' => [
            'full_size_cover' => 'Mostra copertina a grandezza intera',
        ],

        'deleted_banner' => [
            'title' => 'Questa beatmap è stata eliminata.',
            'message' => '(solo i moderatori possono vedere questo)',
        ],

        'details' => [
            'by_artist' => 'di :artist',
            'favourite' => 'Mi piace questa beatmap',
            'favourite_login' => 'Accedi per aggiungere questa beatmap ai preferiti',
            'logged-out' => 'devi accedere se vuoi scaricare le beatmap!',
            'mapped_by' => 'mappata da :mapper',
            'mapped_by_guest' => 'difficoltà guest di :mapper',
            'unfavourite' => 'Non mi piace questa beatmap',
            'updated_timeago' => 'ultimo aggiornamento :timeago',

            'download' => [
                '_' => 'Scarica',
                'direct' => '',
                'no-video' => 'senza Video',
                'video' => 'con Video',
            ],

            'login_required' => [
                'bottom' => 'per vedere altre funzioni',
                'top' => 'Accedi',
            ],
        ],

        'details_date' => [
            'approved' => 'approvata :timeago',
            'loved' => 'amata :timeago',
            'qualified' => 'qualificata :timeago',
            'ranked' => 'classificata :timeago',
            'submitted' => 'inviata :timeago',
            'updated' => 'ultimo aggiornamento :timeago',
        ],

        'favourites' => [
            'limit_reached' => 'Hai troppe beatmap preferite! Rimuovine qualcuna prima di riprovare.',
        ],

        'hype' => [
            'action' => 'Metti hype a questa beatmap se ti sei divertito a giocarla per aiutare a renderla <strong>Classificata</strong>.',

            'current' => [
                '_' => 'Questa mappa è attualmente :status.',

                'status' => [
                    'pending' => 'in attesa',
                    'qualified' => 'qualificata',
                    'wip' => 'work in progress',
                ],
            ],

            'disqualify' => [
                '_' => 'Se trovi un errore in questa beatmap, segnalalo :link.',
            ],

            'report' => [
                '_' => 'Se trovi un problema con questa beatmap, segnalalo :link per avvisare il team.',
                'button' => 'Segnala un Problema',
                'link' => 'qui',
            ],
        ],

        'info' => [
            'description' => 'Descrizione',
            'genre' => 'Genere',
            'language' => 'Lingua',
            'mapper_tags' => 'Etichette del mapper',
            'no_scores' => 'Dati ancora in elaborazione...',
            'nominators' => 'Nominatori',
            'nsfw' => 'Contenuto esplicito',
            'offset' => 'Offset online',
            'points-of-failure' => 'Punti di fallimento',
            'source' => 'Sorgente',
            'storyboard' => 'Questa beatmap contiene storyboard',
            'success-rate' => 'Rateo di successo',
            'user_tags' => 'Etichette degli utenti',
            'video' => 'Questa beatmap contiene video',
        ],

        'nsfw_warning' => [
            'details' => 'Questa beatmap ha contenuti espliciti, offensivi o disturbanti. Vuoi vederla comunque?',
            'title' => 'Contenuto Esplicito',

            'buttons' => [
                'disable' => 'Disabilita avviso',
                'listing' => 'Torna alla lista',
                'show' => 'Mostra',
            ],
        ],

        'scoreboard' => [
            'achieved' => 'ottenuto :when',
            'country' => 'Classifica Nazionale',
            'error' => 'Impossibile caricare la classifica',
            'friend' => 'Classifica Amici',
            'global' => 'Classifica Globale',
            'supporter-link' => 'Clicca <a href=":link">qui</a> per vedere tutte le fantastiche funzionalità che otterrai!',
            'supporter-only' => 'Devi essere un osu!supporter per vedere la classifica nazionale, degli amici, o con mod specifiche!',
            'team' => 'Classifica Squadra',
            'title' => 'Classifica',

            'headers' => [
                'accuracy' => 'Precisione',
                'combo' => 'Combo Massima',
                'miss' => 'Miss',
                'mods' => 'Mod',
                'pin' => 'Fissa',
                'player' => 'Giocatore',
                'pp' => '',
                'rank' => 'Posto',
                'score' => 'Punteggio',
                'score_total' => 'Punteggio Totale',
                'time' => 'Tempo',
            ],

            'no_scores' => [
                'country' => 'Nessuno dal tuo paese ha un punteggio in questa mappa!',
                'friend' => 'Nessuno dei tuoi amici ha un punteggio su questa mappa!',
                'global' => 'Ancora nessun punteggio. Perché non provi a farne uno?',
                'loading' => 'Caricamento punteggi...',
                'team' => 'Nessuno della tua squadra ha un punteggio su questa mappa!',
                'unranked' => 'Beatmap non classificata.',
            ],
            'score' => [
                'first' => 'In testa',
                'own' => 'Il tuo miglior punteggio',
            ],
            'supporter_link' => [
                '_' => 'Clicca :here per scoprire tutte le fantastiche funzionalità che ricevi!',
                'here' => 'qui',
            ],
        ],

        'stats' => [
            'cs' => 'Dimensione Cerchi',
            'cs-mania' => 'Numero di Tasti',
            'drain' => 'Perdita HP',
            'accuracy' => 'Precisione',
            'ar' => 'Velocità Approccio',
            'stars' => 'Difficoltà',
            'total_length' => 'Durata (Lunghezza drenaggio: :hit_length)',
            'bpm' => 'BPM',
            'count_circles' => 'Numero di Cerchi',
            'count_sliders' => 'Numero di Slider',
            'offset' => 'Offset online: :offset',
            'user-rating' => 'Voto degli utenti',
            'rating-spread' => 'Diffusione della valutazione',
            'nominations' => 'Nomine',
            'playcount' => 'Volte giocata',
        ],

        'status' => [
            'ranked' => 'Classificata',
            'approved' => 'Approvata',
            'loved' => 'Amata',
            'qualified' => 'Qualificata',
            'wip' => 'WIP',
            'pending' => 'In Attesa',
            'graveyard' => 'Abbandonata',
        ],
    ],

    'spotlight_badge' => [
        'label' => 'Spotlight',
    ],
];
