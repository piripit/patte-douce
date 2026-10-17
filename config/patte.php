<?php

/*
|--------------------------------------------------------------------------
| Réglages du site (le seul fichier à modifier pour changer de client)
|--------------------------------------------------------------------------
| Tout ce qui est écrit ici (nom, téléphone, services, tarifs…) est repris
| automatiquement dans les pages du site et dans le formulaire de réservation.
| Les valeurs ci-dessous sont des EXEMPLES à remplacer par les vraies.
*/

return [

    'name' => 'Patte Douce',
    'tagline' => 'Promenade, garde et visites à domicile pour chiens',
    'phone' => '06 00 00 00 00',
    'email' => 'contact@pattedouce.fr',
    'area' => 'Angers et alentours',
    'hours' => 'Du lundi au samedi, de 8h à 19h',

    /*
    | Chaque service a un "type" :
    |   - slot : on choisit un créneau horaire (promenade, visite…)
    |   - stay : on choisit une date d'arrivée et de départ (garde d'un jour à plusieurs semaines)
    */
    'services' => [
        [
            'icon' => '🦮',
            'type' => 'slot',
            'name' => 'Promenade',
            'price' => '12 €',
            'per' => 'la sortie de 30 minutes',
            'text' => "Une balade adaptée à l'âge, à l'énergie et au caractère de votre chien.",
        ],
        [
            'icon' => '☀️',
            'type' => 'stay',
            'name' => 'Garde à la journée',
            'price' => '25 €',
            'per' => 'par jour',
            'text' => "Votre chien passe la journée avec moi : sorties, jeux, repos et repas aux horaires habituels. Pour un jour ou plusieurs jours de suite.",
        ],
        [
            'icon' => '🌙',
            'type' => 'stay',
            'name' => 'Garde de nuit et séjours',
            'price' => '35 €',
            'per' => 'par nuit',
            'text' => "Pour un week-end comme pour plusieurs semaines : votre chien garde ses repères et ses habitudes. Le tarif total est confirmé avant le séjour.",
        ],
        [
            'icon' => '🏠',
            'type' => 'slot',
            'name' => 'Visite à domicile',
            'price' => '10 €',
            'per' => 'la visite',
            'text' => "Je passe chez vous pour le repas, l'eau fraîche, un moment de jeu et une sortie.",
        ],
    ],

    // Durée maximale d'un séjour réservable en ligne (au-delà : contact direct)
    'max_stay_days' => 60,

    // Adresse qui reçoit un email à chaque nouvelle demande (laisser vide pour désactiver)
    // À définir dans le fichier .env : PATTE_NOTIFY_TO=votre.adresse@exemple.fr
    'notify_to' => env('PATTE_NOTIFY_TO'),

    // Informations obligatoires pour la page « Mentions légales »
    'legal' => [
        'owner' => '[Nom et prénom de l\'éditeur]',
        'status' => '[Statut, par exemple micro-entrepreneur]',
        'siret' => '[Numéro SIRET]',
        'address' => '[Adresse professionnelle]',
        'host' => '[Nom et adresse de l\'hébergeur du site]',
    ],
];
