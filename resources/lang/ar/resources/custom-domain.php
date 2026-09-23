<?php

return [
    'fields' => [
        'domain' => 'النطاق',
        'is_wildcard' => 'حرف بدل',
        'is_wildcard_helper' => 'مطابقة أي نطاق فرعي لهذا النطاق أيضًا.',
        'is_default' => 'افتراضي',
        'is_default_helper' => 'تستخدم الروابط المختصرة الجديدة هذا النطاق عند عدم اختيار نطاق صراحةً. يمكن أن يكون نطاق واحد فقط افتراضيًا؛ تعيين هذا الخيار يلغيه من أي نطاق آخر. يجب التحقق منه أولًا.',
        'root_redirect_url' => 'رابط إعادة توجيه الجذر',
        'status' => 'تم التحقق',
        'dns_record_type' => 'سجل DNS',
        'last_checked_at' => 'آخر فحص',
    ],
    'actions' => [
        'dns_instructions' => 'تعليمات DNS',
        'verify_now' => 'تحقق الآن',
        'verify_queued' => 'تمت جدولة التحقق.',
        'set_default' => 'تعيين كافتراضي',
        'set_default_success' => 'أصبح :domain النطاق الافتراضي للروابط المختصرة الجديدة.',
        'close' => 'إغلاق',
    ],
    'dns' => [
        'option_txt' => 'الخيار 1 — سجل TXT (موصى به)',
        'option_cname' => 'الخيار 2 — سجل CNAME',
        'type' => 'النوع',
        'host' => 'المضيف',
        'value' => 'القيمة',
        'click_to_copy' => 'انقر للنسخ',
        'copied' => 'تم النسخ!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. اضبط حالة الوكيل على "DNS only" أثناء التحقق.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
