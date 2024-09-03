<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'edit' => [
        'title_compact' => 'ρυθμίσεις',
        'username' => 'όνομα χρήστη',

        'avatar' => [
            'title' => 'Avatar',
            'reset' => '',
            'rules' => 'Παρακαλώ βεβαιωθείτε ότι το avatar σας συμφωνεί με :link. <br/>Αυτό σημαίνει ότι πρέπει να είναι <strong>κατάλληλο για όλες τις ηλικές</strong>.',
            'rules_link' => 'τους κανόνες κοινότητας',
        ],

        'email' => [
            'new' => 'νέο email',
            'new_confirmation' => 'επιβεβαίωση email',
            'title' => 'Email',
            'locked' => [
                '_' => 'Παρακαλούμε επικοινωνήστε με το :accounts εάν χρειάζεστε ενημέρωση του email σας.',
                'accounts' => 'ομάδα υποστήριξης λογαριασμού',
            ],
        ],

        'legacy_api' => [
            'api' => 'api',
            'irc' => 'irc',
            'title' => 'Legacy API',
        ],

        'password' => [
            'current' => 'τρέχων κωδικός',
            'new' => 'νέος κωδικός',
            'new_confirmation' => 'επιβεβαίωση κωδικού',
            'title' => 'Κωδικός',
        ],

        'profile' => [
            'country' => 'χώρα',
            'title' => 'Προφίλ',

            'country_change' => [
                '_' => "Φαίνεται ότι η χώρα του λογαριασμού σας δεν ταιριάζει με τη χώρα διαμονής σας. :update_link.",
                'update_link' => 'Ενημέρωση σε :country',
            ],

            'user' => [
                'user_discord' => '',
                'user_from' => 'τρέχουσα τοποθεσία',
                'user_interests' => 'ενδιαφέροντα',
                'user_occ' => 'ενασχόληση',
                'user_twitter' => '',
                'user_website' => 'ιστοσελίδα',
            ],
        ],

        'signature' => [
            'title' => 'Υπογραφή',
            'update' => 'ενημέρωση',
        ],
    ],

    'github_user' => [
        'info' => "",
        'link' => '',
        'title' => '',
        'unlink' => '',

        'error' => [
            'already_linked' => '',
            'no_contribution' => '',
            'unverified_email' => '',
        ],
    ],

    'notifications' => [
        'beatmapset_discussion_qualified_problem' => 'λαμβάνετε ειδοποιήσεις για νέα προβλήματα σε πιστοποιημένους beatmaps των παρακάτω λειτουργιών',
        'beatmapset_disqualify' => 'λαμβάνετε ειδοποιήσεις για το πότε τα beatmaps των παρακάτω λειτουργιών αποκλείονται',
        'comment_reply' => 'λαμβάνετε ειδοποιήσεις για απαντήσεις στα σχόλιά σας',
        'title' => 'Ειδοποιήσεις',
        'topic_auto_subscribe' => 'αυτόματη ενεργοποίηση ειδοποιήσεων για τα νέα θέματα που δημιουργείτε στο φόρουμ',

        'options' => [
            '_' => 'επιλογές παράδοσης',
            'beatmap_owner_change' => 'δυσκολία επισκέπτη',
            'beatmapset:modding' => 'beatmap modding',
            'channel_message' => 'ιδιωτικά μηνύματα',
            'comment_new' => 'νέα σχόλια',
            'forum_topic_reply' => 'απάντηση θέματος',
            'mail' => 'mail',
            'mapping' => 'beatmap mapper',
            'push' => 'push',
        ],
    ],

    'oauth' => [
        'authorized_clients' => 'εγκεκριμένοι clients',
        'own_clients' => 'οι δικοί σας clients',
        'title' => 'OAuth',
    ],

    'options' => [
        'beatmapset_show_nsfw' => 'απόκρυψη προειδοποιήσεων για ακατάλληλο περιεχόμενο σε beatmaps',
        'beatmapset_title_show_original' => 'εμφάνιση μεταδεδομένων beatmap στην αρχική γλώσσα',
        'title' => 'Ρυθμίσεις',

        'beatmapset_download' => [
            '_' => 'προεπιλεγμένος τύπος λήψης beatmap',
            'all' => 'με βίντεο εάν είναι διαθέσιμο',
            'direct' => 'άνοιγμα με osu!direct',
            'no_video' => 'χωρίς βίντεο',
        ],
    ],

    'playstyles' => [
        'keyboard' => 'πληκτρολόγιο',
        'mouse' => 'ποντίκι',
        'tablet' => 'γραφίδα',
        'title' => 'Τρόπος παιχνιδιού',
        'touch' => 'οθόνη αφής',
    ],

    'privacy' => [
        'friends_only' => 'Αποκλεισμός των ιδιωτικών μηνυμάτων από άτομα που δεν βρίσκονται στη λίστα φίλων σας',
        'hide_online' => 'απόκρυψη παρουσίας',
        'title' => 'Απόρρητο',
    ],

    'security' => [
        'current_session' => 'τρέχουσα',
        'end_session' => 'Λήξη Συνεδρίας',
        'end_session_confirmation' => 'Αυτό θα λήξει τη συνεδρία σας σε αυτή την συσκευή. Είστε σίγουρος;',
        'last_active' => 'Τελευταία ενεργός:',
        'title' => 'Ασφάλεια',
        'web_sessions' => 'συνεδρίες',
    ],

    'update_email' => [
        'update' => 'ενημέρωση',
    ],

    'update_password' => [
        'update' => 'ενημέρωση
',
    ],

    'verification_completed' => [
        'text' => 'Μπορείτε πλέον να κλείσετε αυτήν την καρτέλα/παραάθυρο',
        'title' => 'Η επαλήθευση ολοκληρώθηκε',
    ],

    'verification_invalid' => [
        'title' => 'Μη έγκυρος ή ληξιπρόθεσμος σύνδεσμος',
    ],
];
