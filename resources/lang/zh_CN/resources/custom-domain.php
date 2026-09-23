<?php

return [
    'fields' => [
        'domain' => '域名',
        'is_wildcard' => '通配符',
        'is_wildcard_helper' => '同时匹配该域名的所有子域名。',
        'is_default' => '默认',
        'is_default_helper' => '未明确选择域名时，新的短链接将使用此域名。只能有一个默认域名；设置后会清除其他域名的默认状态。必须先完成验证。',
        'root_redirect_url' => '根路径重定向 URL',
        'status' => '已验证',
        'dns_record_type' => 'DNS 记录',
        'last_checked_at' => '上次检查',
    ],
    'actions' => [
        'dns_instructions' => 'DNS 说明',
        'verify_now' => '立即验证',
        'verify_queued' => '验证已加入队列。',
        'set_default' => '设为默认',
        'set_default_success' => ':domain 现已成为新短链接的默认域名。',
        'close' => '关闭',
    ],
    'dns' => [
        'option_txt' => '选项 1 — TXT 记录（推荐）',
        'option_cname' => '选项 2 — CNAME 记录',
        'type' => '类型',
        'host' => '主机',
        'value' => '值',
        'click_to_copy' => '点击复制',
        'copied' => '已复制！',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. 验证期间请将代理状态设为 "DNS only"。',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
