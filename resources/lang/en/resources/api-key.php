<?php

return [
    'fields' => [
        'name' => 'Name',
        'abilities' => 'Abilities',
        'prefix' => 'Prefix',
        'status' => 'Status',
        'last_used_at' => 'Last Used',
        'expires_at' => 'Expires At',
    ],
    'status' => [
        'active' => 'Active',
        'revoked' => 'Revoked',
        'expired' => 'Expired',
    ],
    'actions' => [
        'revoke' => 'Revoke',
        'rotate' => 'Rotate',
        'rotated_title' => 'Key rotated — copy the new token now, it will not be shown again',
        'created_title' => 'API key created',
        'created_body' => 'Copy this token now — it will not be shown again: :token',
    ],
];
