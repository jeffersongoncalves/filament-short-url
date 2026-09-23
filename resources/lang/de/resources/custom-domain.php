<?php

return [
    'fields' => [
        'domain' => 'Domain',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Auch jede Subdomain dieser Domain berücksichtigen.',
        'is_default' => 'Standard',
        'is_default_helper' => 'Neue Kurz-URLs verwenden diese Domain, wenn keine explizit gewählt wird. Nur eine Domain kann Standard sein; diese Einstellung entfernt sie bei allen anderen Domains. Muss zuerst verifiziert werden.',
        'root_redirect_url' => 'Root-Weiterleitungs-URL',
        'status' => 'Verifiziert',
        'dns_record_type' => 'DNS-Eintrag',
        'last_checked_at' => 'Zuletzt geprüft',
    ],
    'actions' => [
        'dns_instructions' => 'DNS-Anleitung',
        'verify_now' => 'Jetzt verifizieren',
        'verify_queued' => 'Verifizierung eingereiht.',
        'set_default' => 'Als Standard festlegen',
        'set_default_success' => ':domain ist jetzt die Standard-Domain für neue Kurz-URLs.',
        'close' => 'Schließen',
    ],
    'dns' => [
        'option_txt' => 'Option 1 — TXT-Eintrag (empfohlen)',
        'option_cname' => 'Option 2 — CNAME-Eintrag',
        'type' => 'Typ',
        'host' => 'Host',
        'value' => 'Wert',
        'click_to_copy' => 'Zum Kopieren klicken',
        'copied' => 'Kopiert!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Setzen Sie den Proxy-Status während der Verifizierung auf "DNS only".',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
