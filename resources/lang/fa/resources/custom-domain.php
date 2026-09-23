<?php

return [
    'fields' => [
        'domain' => 'دامنه',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'هر زیردامنه‌ای از این دامنه را نیز شامل شود.',
        'is_default' => 'پیش‌فرض',
        'is_default_helper' => 'لینک‌های کوتاه جدید در صورت عدم انتخاب صریح دامنه، از این دامنه استفاده می‌کنند. فقط یک دامنه می‌تواند پیش‌فرض باشد؛ فعال کردن این گزینه آن را از دامنه‌های دیگر حذف می‌کند. ابتدا باید تأیید شود.',
        'root_redirect_url' => 'آدرس تغییر مسیر ریشه',
        'status' => 'تأییدشده',
        'dns_record_type' => 'رکورد DNS',
        'last_checked_at' => 'آخرین بررسی',
    ],
    'actions' => [
        'dns_instructions' => 'راهنمای DNS',
        'verify_now' => 'تأیید اکنون',
        'verify_queued' => 'تأیید در صف قرار گرفت.',
        'set_default' => 'تنظیم به‌عنوان پیش‌فرض',
        'set_default_success' => ':domain اکنون دامنه پیش‌فرض برای لینک‌های کوتاه جدید است.',
        'close' => 'بستن',
    ],
    'dns' => [
        'option_txt' => 'گزینه ۱ — رکورد TXT (پیشنهادی)',
        'option_cname' => 'گزینه ۲ — رکورد CNAME',
        'type' => 'نوع',
        'host' => 'میزبان',
        'value' => 'مقدار',
        'click_to_copy' => 'برای کپی کلیک کنید',
        'copied' => 'کپی شد!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. در حین تأیید، وضعیت پراکسی را روی "DNS only" قرار دهید.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
