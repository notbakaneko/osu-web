<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'all_read' => 'Todas as notificações foram lidas!',
    'delete' => 'Excluir :type',
    'loading' => 'Carregando notificações não lidas...',
    'mark_read' => 'Limpar :type',
    'none' => 'Sem notificações',
    'see_all' => 'ver todas as notificações',
    'see_channel' => 'ir para o chat',
    'verifying' => 'Por favor verifique a sessão para ver as notificações',

    'action_type' => [
        '_' => 'todas',
        'beatmapset' => 'beatmaps',
        'build' => 'versões',
        'channel' => 'chat',
        'forum_topic' => 'fórum',
        'news_post' => 'notícias',
        'team' => 'equipe',
        'user' => 'perfil',
    ],

    'filters' => [
        '_' => 'tudo',
        'beatmapset' => 'beatmaps',
        'build' => 'versões',
        'channel' => 'chat',
        'forum_topic' => 'fórum',
        'news_post' => 'notícias',
        'team' => 'equipe',
        'user' => 'perfil',
    ],

    'item' => [
        'beatmapset' => [
            '_' => 'Beatmap',

            'beatmap_owner_change' => [
                '_' => 'Dificuldade de convidado',
                'beatmap_owner_change' => 'Você agora é dono(a) da dificuldade ":beatmap" para o mapa ":title" ',
                'beatmap_owner_change_compact' => 'Você agora é dono(a) da dificuldade ":beatmap"',
            ],

            'beatmapset_discussion' => [
                '_' => 'Discussão do beatmap',
                'beatmapset_discussion_lock' => 'A discussão do beatmap ":title" foi trancada.',
                'beatmapset_discussion_lock_compact' => 'A discussão foi trancada',
                'beatmapset_discussion_post_new' => ':username publicou uma nova mensagem na discussão do beatmap ":title".',
                'beatmapset_discussion_post_new_empty' => 'Nova publicação em ":title" por :username',
                'beatmapset_discussion_post_new_compact' => 'Nova publicação de :username',
                'beatmapset_discussion_post_new_compact_empty' => 'Nova publicação de :username',
                'beatmapset_discussion_review_new' => 'Nova revisão em ":title" por :username contendo problemas: :problems, sugestões: :suggestions, elogios: :praises',
                'beatmapset_discussion_review_new_compact' => 'Nova revisão por :username contendo problemas: :problems, sugestões: :suggestions, elogios: :praises',
                'beatmapset_discussion_unlock' => 'A discussão do beatmap ":title" foi destrancada.',
                'beatmapset_discussion_unlock_compact' => 'A discussão foi destrancada',

                'review_count' => [
                    'praises' => ':count_delimited elogio|:count_delimited elogios',
                    'problems' => ':count_delimited problema|:count_delimited problemas',
                    'suggestions' => ':count_delimited sugestão|:count_delimited sugestões',
                ],
            ],

            'beatmapset_problem' => [
                '_' => 'Problema do Beatmap Qualificado',
                'beatmapset_discussion_qualified_problem' => 'Reportado por :username em ":title": ":content"',
                'beatmapset_discussion_qualified_problem_empty' => 'Reportado por :username em ":title"',
                'beatmapset_discussion_qualified_problem_compact' => 'Reportado por :username: ":content"',
                'beatmapset_discussion_qualified_problem_compact_empty' => 'Reportado por :username',
            ],

            'beatmapset_state' => [
                '_' => 'Estado do beatmap alterado',
                'beatmapset_disqualify' => 'O beatmap ":title" foi desqualificado por :username.',
                'beatmapset_disqualify_compact' => 'O beatmap foi desqualificado',
                'beatmapset_love' => 'O beatmap ":title" foi promovido a loved',
                'beatmapset_love_compact' => 'O beatmap foi promovido a loved',
                'beatmapset_nominate' => 'O beatmap ":title" foi nomeado por :username.',
                'beatmapset_nominate_compact' => 'O beatmap foi nomeado',
                'beatmapset_qualify' => 'O beatmap ":title" recebeu indicações suficientes e, portanto, está na fila para se tornar ranqueado.',
                'beatmapset_qualify_compact' => 'O beatmap entrou na fila de ranqueamento',
                'beatmapset_rank' => '":title" se tornou ranqueado',
                'beatmapset_rank_compact' => 'O beatmap foi ranqueado',
                'beatmapset_remove_from_loved' => '":title" foi removido dos Loved',
                'beatmapset_remove_from_loved_compact' => 'O beatmap foi removido dos Loved',
                'beatmapset_reset_nominations' => 'Um problema publicado por :username reiniciou a nomeação do beatmap ":title" ',
                'beatmapset_reset_nominations_compact' => 'A nomeação foi reiniciada',
            ],

            'comment' => [
                '_' => 'Novo comentário',

                'comment_new' => ':username comentou ":content" em ":title"',
                'comment_new_compact' => ':username comentou ":content"',
                'comment_reply' => ':username respondeu ":content" em ":title"',
                'comment_reply_compact' => ':username respondeu ":content"',
            ],
        ],

        'channel' => [
            '_' => 'Chat',

            'announcement' => [
                '_' => 'Novo aviso',

                'announce' => [
                    'channel_announcement' => ':username diz ":title"',
                    'channel_announcement_compact' => ':title',
                    'channel_announcement_group' => 'Aviso de :username',
                ],
            ],

            'channel' => [
                '_' => 'Nova mensagem',

                'pm' => [
                    'channel_message' => ':username diz ":title"',
                    'channel_message_compact' => ':title',
                    'channel_message_group' => 'de :username',
                ],
            ],

            'channel_team' => [
                '_' => 'Nova mensagem do time',

                'team' => [
                    'channel_team' => ':username diz ":title"',
                    'channel_team_compact' => ':username diz ":title"',
                    'channel_team_group' => ':username diz ":title"',
                ],
            ],
        ],

        'build' => [
            '_' => 'Registro de Alterações',

            'comment' => [
                '_' => 'Novo comentário',

                'comment_new' => ':username comentou ":content" em ":title"',
                'comment_new_compact' => ':username comentou ":content"',
                'comment_reply' => ':username respondeu ":content" em ":title"',
                'comment_reply_compact' => ':username respondeu ":content"',
            ],
        ],

        'news_post' => [
            '_' => 'Notícias',

            'comment' => [
                '_' => 'Novo comentário',

                'comment_new' => ':username comentou ":content" em ":title"',
                'comment_new_compact' => ':username comentou ":content"',
                'comment_reply' => ':username respondeu ":content" em ":title"',
                'comment_reply_compact' => ':username respondeu ":content"',
            ],
        ],

        'forum_topic' => [
            '_' => 'Tópico do fórum',

            'forum_topic_reply' => [
                '_' => 'Nova resposta no fórum',
                'forum_topic_reply' => ':username respondeu ao tópico ":title" do fórum.',
                'forum_topic_reply_compact' => ':username respondeu',
            ],
        ],

        'team' => [
            'team_application' => [
                '_' => 'Solicitação para participar de equipe',

                'team_application_accept' => "Você agora é membro da equipe :title",
                'team_application_accept_compact' => "Você agora é membro da equipe :title",

                'team_application_group' => 'Atualizações de solicitações para se juntar ao time',

                'team_application_reject' => 'Seu pedido para se juntar à equipe :title foi recusado',
                'team_application_reject_compact' => 'Seu pedido para se juntar à equipe :title foi recusado',
                'team_application_store' => ':title pediu para se juntar ao seu time',
                'team_application_store_compact' => ':title pediu para se juntar ao seu time',
            ],
        ],

        'user' => [
            'user_beatmapset_new' => [
                '_' => 'Novo beatmap',

                'user_beatmapset_new' => 'Novo beatmap ":title" por :username',
                'user_beatmapset_new_compact' => 'Novo beatmap ":title"',
                'user_beatmapset_new_group' => 'Novos beatmaps por :username',

                'user_beatmapset_revive' => 'Beatmap ":title" revivido por :username',
                'user_beatmapset_revive_compact' => 'Beatmap ":title" revivido',
            ],
        ],

        'user_achievement' => [
            '_' => 'Medalhas',

            'user_achievement_unlock' => [
                '_' => 'Nova medalha',
                'user_achievement_unlock' => '":title" desbloqueado!',
                'user_achievement_unlock_compact' => '":title" desbloqueado!',
                'user_achievement_unlock_group' => 'Medalhas desbloqueadas!',
            ],
        ],
    ],

    'mail' => [
        'beatmapset' => [
            'beatmap_owner_change' => [
                'beatmap_owner_change' => 'Você agora é convidado do mapa ":title"',
            ],

            'beatmapset_discussion' => [
                'beatmapset_discussion_lock' => 'A discussão em ":title" foi trancada',
                'beatmapset_discussion_post_new' => 'A discussão em ":title" tem novas atualizações',
                'beatmapset_discussion_unlock' => 'A discussão em ":title" foi destravada',
            ],

            'beatmapset_problem' => [
                'beatmapset_discussion_qualified_problem' => 'Um novo problema foi reportado em ":title"',
            ],

            'beatmapset_state' => [
                'beatmapset_disqualify' => '":title" foi desqualificado',
                'beatmapset_love' => '":title" foi promovido a loved',
                'beatmapset_nominate' => '":title" foi nomeado',
                'beatmapset_qualify' => '":title" ganhou nomeações suficientes e entrou na fila de ranqueamento',
                'beatmapset_rank' => '":title" foi ranqueado',
                'beatmapset_remove_from_loved' => '":title" foi removido dos Loved',
                'beatmapset_reset_nominations' => 'A nomeação de ":title" foi reiniciada',
            ],

            'comment' => [
                'comment_new' => 'Há novos comentários no beatmap ":title"',
            ],
        ],

        'channel' => [
            'announcement' => [
                'channel_announcement' => 'Há um novo anúncio em ":name"',
            ],
            'channel' => [
                'channel_message' => 'Você recebeu uma nova mensagem de :username',
            ],
            'channel_team' => [
                'channel_team' => 'Há uma nova mensagem no time ":name"',
            ],
        ],

        'build' => [
            'comment' => [
                'comment_new' => 'Há novos comentários no registro de alterações ":title"',
            ],
        ],

        'news_post' => [
            'comment' => [
                'comment_new' => 'Há novos comentários na notícia ":title"',
            ],
        ],

        'forum_topic' => [
            'forum_topic_reply' => [
                'forum_topic_reply' => 'Há novas respostas em ":title"',
            ],
        ],

        'team' => [
            'team_application' => [
                'team_application_accept' => "Você agora é membro da equipe :title",
                'team_application_reject' => 'Seu pedido para se juntar à equipe :title foi recusado',
                'team_application_store' => ':title pediu para se juntar ao seu time',
            ],
        ],

        'user' => [
            'user_beatmapset_new' => [
                'user_beatmapset_new' => ':username criou novos beatmaps',
                'user_beatmapset_revive' => ':username reviveu beatmaps',
            ],
        ],
    ],
];
