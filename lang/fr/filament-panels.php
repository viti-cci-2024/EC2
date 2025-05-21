<?php

return [
    'pages' => [
        'dashboard' => [
            'title' => 'Tableau de bord',
        ],
    ],
    
    'layout' => [
        'sidebar' => [
            'groups' => [
                'users' => 'Utilisateurs',
            ],
        ],
    ],
    
    'widgets' => [
        'account' => [
            'welcome' => 'Bienvenue',
            'buttons' => [
                'sign_out' => [
                    'label' => 'Déconnexion',
                ],
            ],
        ],
    ],
    
    'resources' => [
        'pages' => [
            'list' => [
                'title' => 'Liste',
            ],
            'create' => [
                'title' => 'Créer',
                'buttons' => [
                    'create' => [
                        'label' => 'Créer',
                    ],
                ],
            ],
            'edit' => [
                'title' => 'Modifier',
            ],
        ],
    ],
    
    'navigation' => [
        'groups' => [
            'administration' => 'Administration',
            'gestion' => 'Gestion',
        ],
    ],
    

];
