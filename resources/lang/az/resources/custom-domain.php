<?php

return [
    'fields' => [
        'domain' => 'Domen',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Bu domenin istənilən subdomenini də uyğunlaşdır.',
        'is_default' => 'Standart',
        'is_default_helper' => 'Açıq şəkildə domen seçilmədikdə yeni qısa URL-lər bu domendən istifadə edir. Yalnız bir domen standart ola bilər; bunu təyin etmək digər domenlərdən ləğv edir. Əvvəlcə təsdiqlənməlidir.',
        'root_redirect_url' => 'Kök yönləndirmə URL-i',
        'status' => 'Təsdiqlənib',
        'dns_record_type' => 'DNS qeydi',
        'last_checked_at' => 'Son yoxlama',
    ],
    'actions' => [
        'dns_instructions' => 'DNS təlimatları',
        'verify_now' => 'İndi yoxla',
        'verify_queued' => 'Yoxlama növbəyə alındı.',
        'set_default' => 'Standart et',
        'set_default_success' => ':domain artıq yeni qısa URL-lər üçün standart domendir.',
        'close' => 'Bağla',
    ],
    'dns' => [
        'option_txt' => 'Seçim 1 — TXT qeydi (tövsiyə olunur)',
        'option_cname' => 'Seçim 2 — CNAME qeydi',
        'type' => 'Növ',
        'host' => 'Host',
        'value' => 'Dəyər',
        'click_to_copy' => 'Kopyalamaq üçün klikləyin',
        'copied' => 'Kopyalandı!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Yoxlama zamanı proxy statusunu "DNS only" olaraq təyin edin.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
