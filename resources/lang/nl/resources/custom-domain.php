<?php

return [
    'fields' => [
        'domain' => 'Domein',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Ook elk subdomein van dit domein matchen.',
        'is_default' => 'Standaard',
        'is_default_helper' => 'Nieuwe korte URL\'s gebruiken dit domein als er niet expliciet een is gekozen. Slechts één domein kan standaard zijn; deze instelling wist het bij alle andere domeinen. Moet eerst geverifieerd zijn.',
        'root_redirect_url' => 'Root-omleidings-URL',
        'status' => 'Geverifieerd',
        'dns_record_type' => 'DNS-record',
        'last_checked_at' => 'Laatst gecontroleerd',
    ],
    'actions' => [
        'dns_instructions' => 'DNS-instructies',
        'verify_now' => 'Nu verifiëren',
        'verify_queued' => 'Verificatie in de wachtrij gezet.',
        'set_default' => 'Als standaard instellen',
        'set_default_success' => ':domain is nu het standaarddomein voor nieuwe korte URL\'s.',
        'close' => 'Sluiten',
    ],
    'dns' => [
        'option_txt' => 'Optie 1 — TXT-record (aanbevolen)',
        'option_cname' => 'Optie 2 — CNAME-record',
        'type' => 'Type',
        'host' => 'Host',
        'value' => 'Waarde',
        'click_to_copy' => 'Klik om te kopiëren',
        'copied' => 'Gekopieerd!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Zet de proxystatus op "DNS only" tijdens het verifiëren.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
