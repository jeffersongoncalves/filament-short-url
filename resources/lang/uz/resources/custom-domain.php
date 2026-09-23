<?php

return [
    'fields' => [
        'domain' => 'Domen',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Ushbu domenning istalgan subdomeniga ham mos kelsin.',
        'is_default' => 'Standart',
        'is_default_helper' => 'Domen aniq tanlanmaganda yangi qisqa URLʼlar ushbu domendan foydalanadi. Faqat bitta domen standart boʻlishi mumkin; buni yoqish boshqa domenlardagi sozlamani olib tashlaydi. Avval tasdiqlangan boʻlishi kerak.',
        'root_redirect_url' => 'Ildiz yoʻnaltirish URL',
        'status' => 'Tasdiqlangan',
        'dns_record_type' => 'DNS yozuvi',
        'last_checked_at' => 'Oxirgi tekshiruv',
    ],
    'actions' => [
        'dns_instructions' => 'DNS koʻrsatmalari',
        'verify_now' => 'Hozir tekshirish',
        'verify_queued' => 'Tekshiruv navbatga qoʻyildi.',
        'set_default' => 'Standart qilish',
        'set_default_success' => ':domain endi yangi qisqa URLʼlar uchun standart domen.',
        'close' => 'Yopish',
    ],
    'dns' => [
        'option_txt' => '1-variant — TXT yozuvi (tavsiya etiladi)',
        'option_cname' => '2-variant — CNAME yozuvi',
        'type' => 'Turi',
        'host' => 'Xost',
        'value' => 'Qiymat',
        'click_to_copy' => 'Nusxalash uchun bosing',
        'copied' => 'Nusxalandi!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Tekshiruv vaqtida proksi holatini "DNS only" qilib qoʻying.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
