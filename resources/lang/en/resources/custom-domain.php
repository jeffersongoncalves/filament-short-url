<?php

return [
    'fields' => [
        'domain' => 'Domain',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Also match any subdomain of this domain.',
        'root_redirect_url' => 'Root Redirect URL',
        'status' => 'Verified',
        'dns_record_type' => 'DNS Record',
        'last_checked_at' => 'Last Checked',
    ],
    'actions' => [
        'dns_instructions' => 'DNS Instructions',
        'verify_now' => 'Verify Now',
        'verify_queued' => 'Verification queued.',
        'close' => 'Close',
    ],
    'dns' => [
        'option_txt' => 'Option 1 — TXT record (recommended)',
        'option_cname' => 'Option 2 — CNAME record',
        'type' => 'Type',
        'host' => 'Host',
        'value' => 'Value',
        'click_to_copy' => 'Click to copy',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Set proxy status to "DNS only" while verifying.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
