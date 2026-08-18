<?php

return [
    'fields' => [
        'name' => 'Nome',
        'abilities' => 'Permissões',
        'prefix' => 'Prefixo',
        'status' => 'Status',
        'last_used_at' => 'Último Uso',
        'expires_at' => 'Expira Em',
    ],
    'status' => [
        'active' => 'Ativa',
        'revoked' => 'Revogada',
        'expired' => 'Expirada',
    ],
    'actions' => [
        'revoke' => 'Revogar',
        'rotate' => 'Rotacionar',
        'rotated_title' => 'Chave rotacionada — copie o novo token agora, ele não será exibido novamente',
        'created_title' => 'Chave de API criada',
        'created_body' => 'Copie este token agora — ele não será exibido novamente: :token',
    ],
];
