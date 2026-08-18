<?php

return [
    'fields' => [
        'name' => 'Nombre',
        'abilities' => 'Permisos',
        'prefix' => 'Prefijo',
        'status' => 'Estado',
        'last_used_at' => 'Último Uso',
        'expires_at' => 'Expira En',
    ],
    'status' => [
        'active' => 'Activa',
        'revoked' => 'Revocada',
        'expired' => 'Expirada',
    ],
    'actions' => [
        'revoke' => 'Revocar',
        'rotate' => 'Rotar',
        'rotated_title' => 'Clave rotada — copie el nuevo token ahora, no se mostrará de nuevo',
        'created_title' => 'Clave de API creada',
        'created_body' => 'Copie este token ahora — no se mostrará de nuevo: :token',
    ],
];
