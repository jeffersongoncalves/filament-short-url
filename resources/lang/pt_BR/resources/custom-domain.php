<?php

return [
    'fields' => [
        'domain' => 'Domínio',
        'is_wildcard' => 'Curinga',
        'is_wildcard_helper' => 'Também corresponde a qualquer subdomínio deste domínio.',
        'root_redirect_url' => 'URL de Redirecionamento Raiz',
        'status' => 'Verificado',
        'dns_record_type' => 'Registro DNS',
        'last_checked_at' => 'Última Verificação',
    ],
    'actions' => [
        'dns_instructions' => 'Instruções de DNS',
        'verify_now' => 'Verificar Agora',
        'verify_queued' => 'Verificação enfileirada.',
        'close' => 'Fechar',
    ],
    'dns' => [
        'option_txt' => 'Opção 1 — Registro TXT (recomendado)',
        'option_cname' => 'Opção 2 — Registro CNAME',
        'type' => 'Tipo',
        'host' => 'Host',
        'value' => 'Valor',
        'click_to_copy' => 'Clique para copiar',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Deixe o proxy como "DNS only" durante a verificação.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
