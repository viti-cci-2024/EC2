<?php

return [
    'reservation' => [
        'label' => 'Réservation',
        'plural_label' => 'Réservations',
        
        'actions' => [
            'create' => [
                'label' => 'Nouvelle réservation',
            ],
        ],
        
        'table' => [
            'columns' => [
                'numero' => 'N° Réservation',
                'last_name' => 'Nom',
                'bungalow_type' => 'Type de bungalow',
                'start_date' => 'Début',
                'end_date' => 'Fin',
                'person_count' => 'Personnes',
                'created_at' => 'Créée le',
            ],
        ],
    ],
    
    'user-resource' => [
        'label' => 'Utilisateur',
        'plural_label' => 'Utilisateurs',
        
        'actions' => [
            'create' => [
                'label' => 'Nouvel utilisateur',
            ],
        ],
        
        'table' => [
            'columns' => [
                'name' => 'Nom',
                'email' => 'Email',
                'role' => 'Rôle',
                'created_at' => 'Créé le',
            ],
        ],
    ],
];
