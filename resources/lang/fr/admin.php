<?php

return [
    'nav' => [
        'title' => 'Codes créateurs',
        'codes' => 'Codes créateurs',
        'commissions' => 'Commissions',
    ],

    'codes' => [
        'title' => 'Codes créateurs',
        'create' => 'Nouveau code créateur',
        'edit' => 'Modifier le code créateur',
        'view_commissions' => 'Voir les commissions',
        'new' => 'Nouveau code',
        'empty' => 'Aucun code créateur pour le moment.',
        'delete_confirm' => 'Supprimer ce code ?',
        'created' => 'Code créateur créé.',
        'updated' => 'Code créateur mis à jour.',
        'deleted' => 'Code créateur supprimé.',
        'columns' => [
            'code' => 'Code',
            'creator' => 'Créateur',
            'commission' => 'Commission',
            'active' => 'Actif',
            'orders' => 'Commandes associées',
            'total' => 'Total généré',
        ],
        'status' => [
            'active' => 'Actif',
            'inactive' => 'Inactif',
        ],
    ],

    'form' => [
        'creator' => 'Créateur',
        'select_user' => '-- Sélectionner un utilisateur --',
        'code' => 'Code',
        'code_placeholder' => 'Ex : GUIGUI10',
        'commission_rate' => 'Taux de commission (%)',
        'paypal_email' => 'E-mail PayPal (pour le paiement automatique)',
        'paypal_placeholder' => 'createur@paypal.com',
        'paypal_help' => 'Laissez vide si vous préférez payer manuellement (virement, espèces, etc.).',
        'active' => 'Code actif',
    ],

    'actions' => [
        'save' => 'Enregistrer',
        'cancel' => 'Annuler',
        'edit' => 'Modifier',
        'delete' => 'Supprimer',
    ],

    'commissions' => [
        'title' => 'Commissions des créateurs',
        'pending_total' => 'Montant dû',
        'paid_total' => 'Déjà versé',
        'empty' => 'Aucune commission pour le moment.',
        'columns' => [
            'date' => 'Date',
            'order' => 'Commande',
            'creator' => 'Créateur',
            'code' => 'Code',
            'order_amount' => 'Montant de la commande',
            'commission' => 'Commission',
            'status' => 'Statut',
        ],
        'status' => [
            'paid' => 'Payée',
            'pending' => 'En attente',
            'paypal' => 'PayPal : :status',
            'paypal_failed' => 'Échec d’un précédent paiement PayPal',
        ],
        'pay_paypal' => 'Payer via PayPal',
        'pay_paypal_confirm' => 'Envoyer :amount :currency via PayPal à :email ? Ce virement est réel.',
        'mark_paid' => 'Marquer comme payée',
        'marked_paid' => 'Commission marquée comme payée.',
        'paypal_sent' => 'Paiement PayPal envoyé avec succès.',
    ],

    'paypal' => [
        'email_subject' => 'Votre commission de créateur',
        'email_message' => 'Merci pour votre soutien !',
        'note' => 'Commission créateur n°:id',
        'errors' => [
            'missing_credentials' => 'Identifiants PayPal manquants : vérifiez CREATORSCODES_PAYPAL_CLIENT_ID et CREATORSCODES_PAYPAL_CLIENT_SECRET dans le fichier .env, puis exécutez php artisan config:clear.',
            'auth_failed' => 'Échec de l’authentification PayPal : :error',
            'already_paid' => 'Cette commission est déjà marquée comme payée.',
            'no_email' => 'Aucune adresse PayPal renseignée pour ce créateur.',
            'payout_failed' => 'Échec du paiement PayPal : :error',
        ],
    ],
];
