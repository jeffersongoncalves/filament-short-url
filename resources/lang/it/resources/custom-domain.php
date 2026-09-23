<?php

return [
    'fields' => [
        'domain' => 'Dominio',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Includi anche qualsiasi sottodominio di questo dominio.',
        'is_default' => 'Predefinito',
        'is_default_helper' => 'I nuovi URL brevi usano questo dominio quando non ne viene scelto uno esplicitamente. Solo un dominio può essere predefinito; impostarlo lo rimuove da ogni altro dominio. Deve prima essere verificato.',
        'root_redirect_url' => 'URL di reindirizzamento radice',
        'status' => 'Verificato',
        'dns_record_type' => 'Record DNS',
        'last_checked_at' => 'Ultimo controllo',
    ],
    'actions' => [
        'dns_instructions' => 'Istruzioni DNS',
        'verify_now' => 'Verifica ora',
        'verify_queued' => 'Verifica messa in coda.',
        'set_default' => 'Imposta come predefinito',
        'set_default_success' => ':domain è ora il dominio predefinito per i nuovi URL brevi.',
        'close' => 'Chiudi',
    ],
    'dns' => [
        'option_txt' => 'Opzione 1 — record TXT (consigliato)',
        'option_cname' => 'Opzione 2 — record CNAME',
        'type' => 'Tipo',
        'host' => 'Host',
        'value' => 'Valore',
        'click_to_copy' => 'Clicca per copiare',
        'copied' => 'Copiato!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Imposta lo stato del proxy su "DNS only" durante la verifica.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
