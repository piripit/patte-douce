<?php

// Messages d'erreur des formulaires, en français.
return [
    'required' => 'Le champ :attribute est obligatoire.',
    'string' => 'Le champ :attribute doit être un texte.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'date' => 'Le champ :attribute doit être une date valide.',
    'date_format' => 'Le champ :attribute doit respecter le format :format.',
    'after' => 'Le champ :attribute doit être postérieur au champ :date.',
    'after_or_equal' => 'Le champ :attribute doit être une date postérieure ou égale au champ :date.',
    'exists' => 'La valeur choisie pour :attribute n\'est pas valide.',
    'in' => 'La valeur choisie pour :attribute n\'est pas valide.',
    'regex' => 'Le format du champ :attribute n\'est pas valide.',
    'max' => [
        'array' => 'Le champ :attribute ne doit pas contenir plus de :max éléments.',
        'file' => 'Le fichier :attribute ne doit pas dépasser :max Ko.',
        'numeric' => 'Le champ :attribute ne doit pas être supérieur à :max.',
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],
    'min' => [
        'array' => 'Le champ :attribute doit contenir au moins :min éléments.',
        'file' => 'Le fichier :attribute doit faire au moins :min Ko.',
        'numeric' => 'Le champ :attribute doit être au moins égal à :min.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],

    'custom' => [],

    'attributes' => [
        'availability_id' => 'créneau',
        'client_name' => 'nom',
        'client_email' => 'email',
        'client_phone' => 'téléphone',
        'dog_name' => 'nom du chien',
        'service' => 'service',
        'message' => 'message',
        'date' => 'date',
        'start_time' => 'heure de début',
        'end_time' => 'heure de fin',
        'start_date' => 'date d\'arrivée',
        'end_date' => 'date de départ',
        'open_from' => 'date de début',
        'open_to' => 'date de fin',
        'close_from' => 'date de début',
        'close_to' => 'date de fin',
        'capacity' => 'nombre de chiens',
        'email' => 'email',
        'password' => 'mot de passe',
    ],
];
