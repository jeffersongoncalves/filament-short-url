<?php

return [
    'fields' => [
        'domain' => 'Домен',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Також враховувати будь-який піддомен цього домену.',
        'is_default' => 'За замовчуванням',
        'is_default_helper' => 'Нові короткі посилання використовують цей домен, якщо домен не вибрано явно. За замовчуванням може бути лише один домен; увімкнення цієї опції знімає її з інших доменів. Спочатку домен має бути підтверджено.',
        'root_redirect_url' => 'URL переспрямування з кореня',
        'status' => 'Підтверджено',
        'dns_record_type' => 'DNS-запис',
        'last_checked_at' => 'Остання перевірка',
    ],
    'actions' => [
        'dns_instructions' => 'Інструкції з DNS',
        'verify_now' => 'Перевірити зараз',
        'verify_queued' => 'Перевірку поставлено в чергу.',
        'set_default' => 'Зробити доменом за замовчуванням',
        'set_default_success' => ':domain тепер домен за замовчуванням для нових коротких посилань.',
        'close' => 'Закрити',
    ],
    'dns' => [
        'option_txt' => 'Варіант 1 — запис TXT (рекомендовано)',
        'option_cname' => 'Варіант 2 — запис CNAME',
        'type' => 'Тип',
        'host' => 'Хост',
        'value' => 'Значення',
        'click_to_copy' => 'Натисніть, щоб скопіювати',
        'copied' => 'Скопійовано!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. На час перевірки встановіть статус проксі "DNS only".',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
