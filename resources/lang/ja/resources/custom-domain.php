<?php

return [
    'fields' => [
        'domain' => 'ドメイン',
        'is_wildcard' => 'ワイルドカード',
        'is_wildcard_helper' => 'このドメインの任意のサブドメインにも一致させます。',
        'is_default' => 'デフォルト',
        'is_default_helper' => 'ドメインが明示的に選択されていない場合、新しい短縮 URL はこのドメインを使用します。デフォルトにできるドメインは 1 つだけで、設定すると他のドメインからは解除されます。事前に検証が必要です。',
        'root_redirect_url' => 'ルートのリダイレクト URL',
        'status' => '検証済み',
        'dns_record_type' => 'DNS レコード',
        'last_checked_at' => '最終確認',
    ],
    'actions' => [
        'dns_instructions' => 'DNS 設定手順',
        'verify_now' => '今すぐ検証',
        'verify_queued' => '検証をキューに追加しました。',
        'set_default' => 'デフォルトに設定',
        'set_default_success' => ':domain が新しい短縮 URL のデフォルトドメインになりました。',
        'close' => '閉じる',
    ],
    'dns' => [
        'option_txt' => 'オプション 1 — TXT レコード（推奨）',
        'option_cname' => 'オプション 2 — CNAME レコード',
        'type' => 'タイプ',
        'host' => 'ホスト',
        'value' => '値',
        'click_to_copy' => 'クリックしてコピー',
        'copied' => 'コピーしました！',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. 検証中はプロキシのステータスを "DNS only" に設定してください。',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
