<?php

return [
    'fields' => [
        'domain' => 'Alan adı',
        'is_wildcard' => 'Joker',
        'is_wildcard_helper' => 'Bu alan adının tüm alt alan adlarıyla da eşleş.',
        'is_default' => 'Varsayılan',
        'is_default_helper' => 'Açıkça bir alan adı seçilmediğinde yeni kısa URL\'ler bu alan adını kullanır. Yalnızca bir alan adı varsayılan olabilir; bunu ayarlamak diğer alan adlarındaki ayarı kaldırır. Önce doğrulanmalıdır.',
        'root_redirect_url' => 'Kök yönlendirme URL\'si',
        'status' => 'Doğrulandı',
        'dns_record_type' => 'DNS kaydı',
        'last_checked_at' => 'Son kontrol',
    ],
    'actions' => [
        'dns_instructions' => 'DNS talimatları',
        'verify_now' => 'Şimdi doğrula',
        'verify_queued' => 'Doğrulama kuyruğa alındı.',
        'set_default' => 'Varsayılan yap',
        'set_default_success' => ':domain artık yeni kısa URL\'ler için varsayılan alan adı.',
        'close' => 'Kapat',
    ],
    'dns' => [
        'option_txt' => 'Seçenek 1 — TXT kaydı (önerilen)',
        'option_cname' => 'Seçenek 2 — CNAME kaydı',
        'type' => 'Tür',
        'host' => 'Sunucu',
        'value' => 'Değer',
        'click_to_copy' => 'Kopyalamak için tıklayın',
        'copied' => 'Kopyalandı!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Doğrulama sırasında proxy durumunu "DNS only" olarak ayarlayın.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
