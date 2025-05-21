<?php

return [
    'pagination' => [
        'label' => 'Navigation par pagination',
        'overview' => 'Affichage de :first à :last sur :total résultats',
        'per_page' => ["Par page"],
    ],
    
    'fields' => [
        'search' => [
            'label' => 'Rechercher',
            'placeholder' => 'Rechercher',
        ],
    ],
    
    'actions' => [
        'filter' => [
            'label' => 'Filtrer',
        ],
        'create' => [
            'label' => 'Nouvelle réservation',
        ],
    ],
    
    'empty' => [
        'heading' => 'Aucun résultat trouvé',
    ],
    
    'filters' => [
        'buttons' => [
            'reset' => [
                'label' => 'Réinitialiser les filtres',
            ],
        ],
    ],
    
    'selection_indicator' => [
        'selected_count' => '1 élément sélectionné|:count éléments sélectionnés',
        'buttons' => [
            'select_all' => [
                'label' => 'Sélectionner les :count éléments',
            ],
            'deselect_all' => [
                'label' => 'Désélectionner tout',
            ],
        ],
    ],
];
