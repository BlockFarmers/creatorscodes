<?php

return [
    'nav' => [
        'title' => 'Creators Codes',
        'codes' => 'Creators Codes',
        'commissions' => 'Commissions',
    ],

    'codes' => [
        'title' => 'Creators codes',
        'create' => 'New creator code',
        'edit' => 'Edit creator code',
        'view_commissions' => 'View commissions',
        'new' => 'New code',
        'empty' => 'No creator code yet.',
        'delete_confirm' => 'Delete this code?',
        'created' => 'Creator code created.',
        'updated' => 'Creator code updated.',
        'deleted' => 'Creator code deleted.',
        'columns' => [
            'code' => 'Code',
            'creator' => 'Creator',
            'commission' => 'Commission',
            'active' => 'Active',
            'orders' => 'Assigned orders',
            'total' => 'Total generated',
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],
    ],

    'form' => [
        'creator' => 'Creator',
        'select_user' => '-- Select a user --',
        'code' => 'Code',
        'code_placeholder' => 'Ex: GUIGUI10',
        'commission_rate' => 'Commission rate (%)',
        'paypal_email' => 'PayPal email (for automatic payout)',
        'paypal_placeholder' => 'creator@paypal.com',
        'paypal_help' => 'Leave blank if you prefer to pay manually (bank transfer, cash, etc.).',
        'active' => 'Active code',
    ],

    'actions' => [
        'save' => 'Save',
        'cancel' => 'Cancel',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    'commissions' => [
        'title' => 'Creator commissions',
        'pending_total' => 'Amount due',
        'paid_total' => 'Already paid',
        'empty' => 'No commission yet.',
        'columns' => [
            'date' => 'Date',
            'order' => 'Order',
            'creator' => 'Creator',
            'code' => 'Code',
            'order_amount' => 'Order amount',
            'commission' => 'Commission',
            'status' => 'Status',
        ],
        'status' => [
            'paid' => 'Paid',
            'pending' => 'Pending',
            'paypal' => 'PayPal: :status',
            'paypal_failed' => 'Previous PayPal payout failed',
        ],
        'pay_paypal' => 'Pay via PayPal',
        'pay_paypal_confirm' => 'Send :amount :currency via PayPal to :email? This is a real transfer.',
        'mark_paid' => 'Mark as paid',
        'marked_paid' => 'Commission marked as paid.',
        'paypal_sent' => 'PayPal payout sent successfully.',
    ],

    'paypal' => [
        'email_subject' => 'Your creator commission',
        'email_message' => 'Thanks for your support!',
        'note' => 'Creator commission #:id',
        'errors' => [
            'missing_credentials' => 'Missing PayPal credentials: check CREATORSCODES_PAYPAL_CLIENT_ID and CREATORSCODES_PAYPAL_CLIENT_SECRET in the .env file, then run php artisan config:clear.',
            'auth_failed' => 'PayPal authentication failed: :error',
            'already_paid' => 'This commission is already marked as paid.',
            'no_email' => 'No PayPal address provided for this creator.',
            'payout_failed' => 'PayPal payout failed: :error',
        ],
    ],
];
