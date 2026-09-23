<?php

return [
    'fields' => [
        'domain' => 'Домен',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Также учитывать любой поддомен этого домена.',
        'is_default' => 'По умолчанию',
        'is_default_helper' => 'Новые короткие ссылки используют этот домен, если домен не выбран явно. По умолчанию может быть только один домен; включение этой опции снимает её с остальных доменов. Сначала домен должен быть подтверждён.',
        'root_redirect_url' => 'URL перенаправления с корня',
        'status' => 'Подтверждён',
        'dns_record_type' => 'DNS-запись',
        'last_checked_at' => 'Последняя проверка',
    ],
    'actions' => [
        'dns_instructions' => 'Инструкции по DNS',
        'verify_now' => 'Проверить сейчас',
        'verify_queued' => 'Проверка поставлена в очередь.',
        'set_default' => 'Сделать доменом по умолчанию',
        'set_default_success' => ':domain теперь домен по умолчанию для новых коротких ссылок.',
        'close' => 'Закрыть',
    ],
    'dns' => [
        'option_txt' => 'Вариант 1 — запись TXT (рекомендуется)',
        'option_cname' => 'Вариант 2 — запись CNAME',
        'type' => 'Тип',
        'host' => 'Хост',
        'value' => 'Значение',
        'click_to_copy' => 'Нажмите, чтобы скопировать',
        'copied' => 'Скопировано!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. На время проверки установите статус прокси "DNS only".',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
