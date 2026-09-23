<?php

return [
    'fields' => [
        'domain' => 'Domena',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Dopasowuj także każdą subdomenę tej domeny.',
        'is_default' => 'Domyślna',
        'is_default_helper' => 'Nowe krótkie URL-e używają tej domeny, gdy żadna nie zostanie wybrana. Tylko jedna domena może być domyślna; ustawienie tej opcji usuwa ją z pozostałych domen. Domena musi być najpierw zweryfikowana.',
        'root_redirect_url' => 'URL przekierowania głównego',
        'status' => 'Zweryfikowana',
        'dns_record_type' => 'Rekord DNS',
        'last_checked_at' => 'Ostatnie sprawdzenie',
    ],
    'actions' => [
        'dns_instructions' => 'Instrukcje DNS',
        'verify_now' => 'Zweryfikuj teraz',
        'verify_queued' => 'Weryfikacja dodana do kolejki.',
        'set_default' => 'Ustaw jako domyślną',
        'set_default_success' => ':domain jest teraz domyślną domeną dla nowych krótkich URL-i.',
        'close' => 'Zamknij',
    ],
    'dns' => [
        'option_txt' => 'Opcja 1 — rekord TXT (zalecane)',
        'option_cname' => 'Opcja 2 — rekord CNAME',
        'type' => 'Typ',
        'host' => 'Host',
        'value' => 'Wartość',
        'click_to_copy' => 'Kliknij, aby skopiować',
        'copied' => 'Skopiowano!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Podczas weryfikacji ustaw status proxy na "DNS only".',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
