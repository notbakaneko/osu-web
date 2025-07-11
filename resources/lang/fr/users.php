<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'deleted' => '[utilisateur supprimé]',

    'beatmapset_activities' => [
        'title' => "Historique de modding de :user",
        'title_compact' => 'Modding',

        'discussions' => [
            'title_recent' => 'Discussions commencées récemment',
        ],

        'events' => [
            'title_recent' => 'Événements récents',
        ],

        'posts' => [
            'title_recent' => 'Posts récents',
        ],

        'votes_received' => [
            'title_most' => 'Les mieux notées (les 3 derniers mois)',
        ],

        'votes_made' => [
            'title_most' => 'Les mieux notées (les 3 derniers mois)',
        ],
    ],

    'blocks' => [
        'banner_text' => 'Vous avez bloqué cet utilisateur.',
        'comment_text' => 'Ce commentaire est masqué.',
        'blocked_count' => 'utilisateurs bloqués (:count)',
        'hide_profile' => 'Masquer le profil',
        'hide_comment' => 'masquer',
        'forum_post_text' => 'Ce post est masqué.',
        'not_blocked' => 'Cet utilisateur n’est pas bloqué.',
        'show_profile' => 'Afficher le profil',
        'show_comment' => 'afficher',
        'too_many' => 'Limite de blocages atteinte.',
        'button' => [
            'block' => 'Bloquer',
            'unblock' => 'Débloquer',
        ],
    ],

    'card' => [
        'gift_supporter' => 'Offrir un tag supporter',
        'loading' => 'Chargement...',
        'send_message' => 'Envoyer un message',
    ],

    'create' => [
        'form' => [
            'password' => 'mot de passe',
            'password_confirmation' => 'confirmation du mot de passe',
            'submit' => 'créer un compte',
            'user_email' => 'e-mail',
            'user_email_confirmation' => 'confirmation de l\'adresse e-mail',
            'username' => 'nom d\'utilisateur',

            'tos_notice' => [
                '_' => 'en créant un compte, vous acceptez les :link',
                'link' => 'conditions générales d\'utilisation',
            ],
        ],
    ],

    'disabled' => [
        'title' => 'Oh-oh ! Il semble que votre compte ait été désactivé.',
        'warning' => "Dans le cas où vous avez enfreint une règle, veuillez noter qu'il y a généralement une période d'un mois pendant laquelle nous n'accepterons aucune demande de réactivation. Après cette période, vous êtes libre de nous contacter si vous le jugez nécessaire. Veuillez noter que la création de nouveaux comptes entraînera une <strong>prolongation de ce délai de récupération d'un mois</strong>. Veuillez également noter que pour <strong>chaque compte que vous créez, vous enfreignez à nouveau les règles</strong>. Nous vous suggérons fortement de ne pas suivre cette voie !",

        'if_mistake' => [
            '_' => 'Si vous pensez qu\'il s\'agit d\'une erreur, vous êtes invité à nous contacter (via :email ou en cliquant sur le "?" dans le coin inférieur droit de cette page). Veuillez noter que nous sommes confiants en nos actions, car elles reposent sur des données solides. Nous nous réservons le droit de ne pas tenir compte de votre demande si nous pensons que vous êtes délibérément malhonnête.',
            'email' => 'e-mail',
        ],

        'reasons' => [
            'compromised' => 'Votre compte a été considéré comme compromis. Il est désactivé temporairement en attendant que son identité soit confirmée.',
            'opening' => 'Il y a plusieurs raisons qui peuvent conduire à la désactivation de votre compte :',

            'tos' => [
                '_' => 'Vous avez enfreint une ou plusieurs de nos :community_rules ou des :tos.',
                'community_rules' => 'règles de la communauté',
                'tos' => 'conditions générales d\'utilisation',
            ],
        ],
    ],

    'filtering' => [
        'by_game_mode' => 'Membres par mode de jeu',
    ],

    'force_reactivation' => [
        'reason' => [
            'inactive' => "Votre compte n'a pas été utilisé depuis longtemps.",
            'inactive_different_country' => "Votre compte n'a pas été utilisé depuis longtemps.",
        ],
    ],

    'login' => [
        '_' => 'Se connecter',
        'button' => 'Se connecter',
        'button_posting' => 'Connexion...',
        'email_login_disabled' => 'La connexion par e-mail est actuellement désactivée. Veuillez utiliser votre nom d\'utilisateur à la place.',
        'failed' => 'Identifiants incorrects',
        'forgot' => 'Mot de passe oublié ?',
        'info' => 'Veuillez vous connecter pour continuer',
        'invalid_captcha' => 'Trop de tentatives de connexion ont échoué, veuillez compléter le captcha et réessayer. (Rafraîchissez la page si vous ne pouvez pas voir le captcha)',
        'locked_ip' => 'Votre adresse IP est bloquée. Merci d\'attendre quelques minutes.',
        'password' => 'Mot de passe',
        'register' => "Vous n'avez pas de compte osu! ? Inscrivez-vous maintenant",
        'remember' => 'Se souvenir de cet ordinateur',
        'title' => 'Veuillez vous connecter pour continuer',
        'username' => 'Nom d\'utilisateur',

        'beta' => [
            'main' => 'L\'accès à la version bêta est actuellement réservé à des utilisateurs privilégiés.',
            'small' => '(les osu!supporters l\'obtiendront bientôt)',
        ],
    ],

    'ogp' => [
        'modding_description' => 'Beatmaps : :counts',
        'modding_description_empty' => 'L\'utilisateur n\'a pas de beatmaps...',

        'description' => [
            '_' => 'Rang (:ruleset): :global | :country',
            'country' => 'Pays :rank',
            'global' => 'Global :rank',
        ],
    ],

    'posts' => [
        'title' => 'Posts de :username',
    ],

    'anonymous' => [
        'login_link' => 'cliquez pour vous connecter',
        'login_text' => 'se connecter',
        'username' => 'Invité',
        'error' => 'Vous devez être connecté pour effectuer cette action.',
    ],
    'logout_confirm' => 'Êtes-vous sûr de vouloir vous déconnecter ? :(',
    'report' => [
        'button_text' => 'Signaler',
        'comments' => 'Commentaires',
        'placeholder' => 'Veuillez fournir toute information que vous pensez utile.',
        'reason' => 'Raison',
        'thanks' => 'Merci pour votre signalement !',
        'title' => 'Signaler :username ?',

        'actions' => [
            'send' => 'Envoyer le rapport',
            'cancel' => 'Annuler',
        ],

        'options' => [
            'cheating' => 'Anti-jeu / Triche',
            'inappropriate_chat' => 'Comportement inapproprié dans le tchat',
            'insults' => 'M’insulte / insulte les autres',
            'multiple_accounts' => 'Utilisation de plusieurs comptes',
            'nonsense' => 'Absurdités répétées',
            'other' => 'Autre (détaillez ci-dessous)',
            'spam' => 'Spam',
            'unwanted_content' => 'Contenu inapproprié',
        ],
    ],
    'restricted_banner' => [
        'title' => 'Votre compte a été restreint !',
        'message' => 'Lorsque vous êtes restreint, vous ne pouvez pas interagir avec les autres joueurs et vos scores ne seront visibles que par vous-même. Cette restriction est souvent le résultat d\'un processus automatique et sera généralement levée sous 24 heures. :link',
        'message_link' => 'Consultez cette page pour en savoir plus.',
    ],
    'show' => [
        'age' => ':age ans',
        'change_avatar' => 'changer votre avatar !',
        'first_members' => 'Ici depuis le début',
        'is_developer' => 'osu!developer',
        'is_supporter' => 'osu!supporter',
        'joined_at' => 'Ici depuis :date',
        'lastvisit' => 'Vu pour la dernière fois :date',
        'lastvisit_online' => 'Actuellement en ligne',
        'missingtext' => 'Vous avez peut-être fait une faute de frappe ! (ou l\'utilisateur est banni)',
        'origin_country' => 'De :country',
        'previous_usernames' => 'Anciennement connu en tant que',
        'plays_with' => 'Joue avec :devices',

        'comments_count' => [
            '_' => 'A publié :link',
            'count' => ':count_delimited commentaire|:count_delimited commentaires',
        ],
        'cover' => [
            'to_0' => 'Cacher la bannière',
            'to_1' => 'Afficher la bannière',
        ],
        'daily_challenge' => [
            'daily' => 'Série quotidienne',
            'daily_streak_best' => 'Meilleure série quotidienne',
            'daily_streak_current' => 'Série quotidienne actuelle',
            'playcount' => 'Participations totales',
            'title' => 'Défi\ndu Jour',
            'top_10p_placements' => 'Placements dans le Top 10%',
            'top_50p_placements' => 'Placements dans le Top 50%',
            'weekly' => 'Série hebdomadaire',
            'weekly_streak_best' => 'Meilleure série hebdomadaire',
            'weekly_streak_current' => 'Série hebdomadaire actuelle',

            'unit' => [
                'day' => ':value j',
                'week' => ':value sem',
            ],
        ],
        'edit' => [
            'cover' => [
                'button' => 'Changer la bannière du profil',
                'defaults_info' => 'D\'autres options de bannières seront disponibles à l\'avenir',
                'holdover_remove_confirm' => "La bannière précédemment sélectionnée n'est plus disponible. Vous ne pourrez plus la réutiliser une fois que vous l'aurez changée. Êtes-vous sûr ?",
                'title' => 'Bannière',

                'upload' => [
                    'broken_file' => 'Impossible de traiter l\'image. Vérifiez l\'image mise en ligne et réessayez.',
                    'button' => 'Mettre en ligne une image',
                    'dropzone' => 'Déplacez ici pour mettre en ligne',
                    'dropzone_info' => 'Vous pouvez aussi déplacer l\'image ici pour la mettre en ligne',
                    'size_info' => 'La taille de la bannière devrait être de 2000x500',
                    'too_large' => 'Le fichier est trop volumineux.',
                    'unsupported_format' => 'Format non pris en charge.',

                    'restriction_info' => [
                        '_' => 'Mise en ligne disponible pour les :link uniquement',
                        'link' => 'osu!supporters',
                    ],
                ],
            ],

            'default_playmode' => [
                'is_default_tooltip' => 'mode de jeu par défaut',
                'set' => 'définir :mode comme mode de jeu par défaut',
            ],

            'hue' => [
                'reset_no_supporter' => 'Voulez-vous vraiment réinitialiser la couleur de votre profil ? Vous devrez obtenir un tag osu!supporter pour la changer à nouveau.',
                'title' => 'Couleur',

                'supporter' => [
                    '_' => 'Les couleurs personnalisées ne sont disponibles que pour les :link',
                    'link' => 'osu!supporters',
                ],
            ],
        ],

        'extra' => [
            'none' => 'aucun',
            'unranked' => 'Aucune partie récente',

            'achievements' => [
                'achieved-on' => 'Obtenue le :date',
                'locked' => 'Verrouillée',
                'title' => 'Médailles',
            ],
            'beatmaps' => [
                'by_artist' => 'par :artist',
                'title' => 'Beatmaps',

                'favourite' => [
                    'title' => 'Beatmaps favorites',
                ],
                'graveyard' => [
                    'title' => 'Beatmaps dans le cimetière',
                ],
                'guest' => [
                    'title' => 'Participation aux beatmaps',
                ],
                'loved' => [
                    'title' => 'Beatmaps loved',
                ],
                'nominated' => [
                    'title' => 'Beatmaps classées nominées',
                ],
                'pending' => [
                    'title' => 'Beatmaps en attente',
                ],
                'ranked' => [
                    'title' => 'Beatmaps classées et approuvées',
                ],
            ],
            'discussions' => [
                'title' => 'Discussions',
                'title_longer' => 'Discussions récentes',
                'show_more' => 'voir plus de discussions',
            ],
            'events' => [
                'title' => 'Événements',
                'title_longer' => 'Événements récents',
                'show_more' => 'voir plus d\'événements',
            ],
            'historical' => [
                'title' => 'Historique',

                'monthly_playcounts' => [
                    'title' => 'Historique des parties',
                    'count_label' => 'Parties',
                ],
                'most_played' => [
                    'count' => 'nombre de parties',
                    'title' => 'Beatmaps les plus jouées',
                ],
                'recent_plays' => [
                    'accuracy' => 'précision : :percentage',
                    'title' => 'Parties récentes (dernières 24h)',
                ],
                'replays_watched_counts' => [
                    'title' => 'Historique des replays regardés',
                    'count_label' => 'Replays regardés',
                ],
            ],
            'kudosu' => [
                'recent_entries' => 'Historique de Kudosu récents',
                'title' => 'Kudosu!',
                'total' => 'Kudosu reçus au total',

                'entry' => [
                    'amount' => ':amount kudosu',
                    'empty' => "Cet utilisateur n'a jamais reçu de kudosu !",

                    'beatmap_discussion' => [
                        'allow_kudosu' => [
                            'give' => 'Reçu :amount kudosu du post de modding :post',
                        ],

                        'deny_kudosu' => [
                            'reset' => 'Refus de :amount kudosu du post :post',
                        ],

                        'delete' => [
                            'reset' => 'Perte de :amount kudosu suite à la suppression du post :post',
                        ],

                        'restore' => [
                            'give' => 'Réception de :amount kudosu suite à la restauration du post :post',
                        ],

                        'vote' => [
                            'give' => 'Réception de :amount kudosu suite aux votes reçus dans le post :post',
                            'reset' => 'Perte de :amount kudosu suite aux votes perdus dans le post :post',
                        ],

                        'recalculate' => [
                            'give' => 'Réception de :amount suite au recalcul des votes du post :post',
                            'reset' => 'Perte de :amount suite au recalcul des votes du post :post',
                        ],
                    ],

                    'forum_post' => [
                        'give' => 'A reçu :amount de :giver pour un post sur :post',
                        'reset' => 'Kudosu réinitialisé par :giver pour le post :post',
                        'revoke' => 'Kudosu refusé par :giver pour le post sur :post',
                    ],
                ],

                'total_info' => [
                    '_' => 'Selon la contribution que l\'utilisateur a apportée à la modération de beatmaps. Voir :link pour plus d\'informations.',
                    'link' => 'cette page',
                ],
            ],
            'me' => [
                'title' => 'moi !',
            ],
            'medals' => [
                'empty' => "Cet utilisateur n'en a encore jamais reçu. ;_;",
                'recent' => 'Les plus récentes',
                'title' => 'Médailles',
            ],
            'playlists' => [
                'title' => 'Parties avec playlist',
            ],
            'posts' => [
                'title' => 'Posts',
                'title_longer' => 'Posts récents',
                'show_more' => 'voir plus de posts',
            ],
            'recent_activity' => [
                'title' => 'Activité récente',
            ],
            'realtime' => [
                'title' => 'Parties multijoueur',
            ],
            'top_ranks' => [
                'download_replay' => 'Télécharger le replay',
                'not_ranked' => 'Seules les beatmaps classées accordent des pp',
                'pp_weight' => 'pondéré :percentage',
                'view_details' => 'Voir les détails',
                'title' => 'Classements',

                'best' => [
                    'title' => 'Meilleures performances',
                ],
                'first' => [
                    'title' => 'Premières places',
                ],
                'pin' => [
                    'to_0' => 'Désépingler',
                    'to_0_done' => 'Score désépinglé',
                    'to_1' => 'Épingler',
                    'to_1_done' => 'Score épinglé',
                ],
                'pinned' => [
                    'title' => 'Scores épinglés',
                ],
            ],
            'votes' => [
                'given' => 'Votes donnés (3 derniers mois)',
                'received' => 'Votes reçus (3 derniers mois)',
                'title' => 'Votes',
                'title_longer' => 'Votes récents',
                'vote_count' => ':count_delimited vote|:count_delimited votes',
            ],
            'account_standing' => [
                'title' => 'État du compte',
                'bad_standing' => "Le compte de :username n'est pas dans un bon état :(",
                'remaining_silence' => '<strong>:username</strong> pourra de nouveau parler dans :duration.',

                'recent_infringements' => [
                    'title' => 'Sanctions récentes',
                    'date' => 'date',
                    'action' => 'sanction',
                    'length' => 'durée',
                    'length_indefinite' => 'Indéterminée',
                    'description' => 'description',
                    'actor' => 'par :username',

                    'actions' => [
                        'restriction' => 'Restriction',
                        'silence' => 'Silence',
                        'tournament_ban' => 'Bannissement de tournoi',
                        'note' => 'Note',
                    ],
                ],
            ],
        ],

        'info' => [
            'discord' => '',
            'interests' => 'Centres d\'intérêt',
            'location' => 'Emplacement actuel',
            'occupation' => 'Occupation',
            'twitter' => '',
            'website' => 'Site Internet',
        ],
        'not_found' => [
            'reason_1' => 'Il a peut-être changé de nom d\'utilisateur.',
            'reason_2' => 'Ce compte est peut-être temporairement indisponible pour des raisons de sécurité ou d\'abus.',
            'reason_3' => 'Vous avez peut-être fait une faute de frappe !',
            'reason_header' => 'Il existe plusieurs raisons possibles à cela :',
            'title' => 'Utilisateur non trouvé ! ;_;',
        ],
        'page' => [
            'button' => 'Modifier le profil',
            'description' => '<strong>moi !</strong> est une zone personnalisable du profil.',
            'edit_big' => 'Éditez-moi !',
            'placeholder' => 'Tapez ici le contenu de votre page',

            'restriction_info' => [
                '_' => 'Vous devez être un :link pour déverrouiller cette fonctionnalité.',
                'link' => 'osu!supporter',
            ],
        ],
        'post_count' => [
            '_' => 'A contribué à :link',
            'count' => ':count_delimited post sur le forum|:count_delimited posts sur le forum',
        ],
        'rank' => [
            'country' => 'Classement national en :mode',
            'country_simple' => 'Classement Pays',
            'global' => 'Classement global en :mode',
            'global_simple' => 'Classement global',
            'highest' => 'Meilleur rang : :rank le :date',
        ],
        'season_stats' => [
            'division_top_percentage' => 'Top :value',
            'total_score' => 'Score total',
        ],
        'stats' => [
            'hit_accuracy' => 'Précision',
            'hits_per_play' => '',
            'level' => 'Niveau :level',
            'level_progress' => 'Progression jusqu’au prochain niveau',
            'maximum_combo' => 'Combo maximum',
            'medals' => 'Médailles',
            'play_count' => 'Nombres de parties',
            'play_time' => 'Temps de jeu total',
            'ranked_score' => 'Score classé',
            'replays_watched_by_others' => 'Replays regardés par les autres',
            'score_ranks' => 'Classements de Scores',
            'total_hits' => 'Nombre de clics',
            'total_score' => 'Score total',
            // modding stats
            'graveyard_beatmapset_count' => 'Beatmaps dans le cimetière',
            'loved_beatmapset_count' => 'Beatmaps loved',
            'pending_beatmapset_count' => 'Beatmaps en attente',
            'ranked_beatmapset_count' => 'Beatmaps classées et approuvées',
        ],
    ],

    'silenced_banner' => [
        'title' => 'Vous êtes actuellement réduit au silence.',
        'message' => 'Certaines actions peuvent être indisponibles.',
    ],

    'status' => [
        'all' => 'Tous',
        'online' => 'En ligne',
        'offline' => 'Hors-ligne',
    ],
    'store' => [
        'from_client' => 'veuillez vous inscrire via le client du jeu à la place !',
        'from_web' => 'veuillez compléter votre inscription en utilisant le site Web d\'osu!',
        'saved' => 'Utilisateur créé',
    ],
    'verify' => [
        'title' => 'Vérification de compte',
    ],

    'view_mode' => [
        'brick' => 'Vue brique',
        'card' => 'Vue en carte',
        'list' => 'Vue en liste',
    ],
];
