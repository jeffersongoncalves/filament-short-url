<?php

return [
    'fields' => [
        'domain' => 'डोमेन',
        'is_wildcard' => 'वाइल्डकार्ड',
        'is_wildcard_helper' => 'इस डोमेन के किसी भी सबडोमेन से भी मिलान करें।',
        'is_default' => 'डिफ़ॉल्ट',
        'is_default_helper' => 'जब कोई डोमेन स्पष्ट रूप से नहीं चुना जाता, तो नए छोटे URL इस डोमेन का उपयोग करते हैं। केवल एक डोमेन डिफ़ॉल्ट हो सकता है; इसे सेट करने से अन्य डोमेन से यह हट जाता है। पहले सत्यापित होना आवश्यक है।',
        'root_redirect_url' => 'रूट रीडायरेक्ट URL',
        'status' => 'सत्यापित',
        'dns_record_type' => 'DNS रिकॉर्ड',
        'last_checked_at' => 'अंतिम जाँच',
    ],
    'actions' => [
        'dns_instructions' => 'DNS निर्देश',
        'verify_now' => 'अभी सत्यापित करें',
        'verify_queued' => 'सत्यापन क्यू में डाला गया।',
        'set_default' => 'डिफ़ॉल्ट बनाएँ',
        'set_default_success' => ':domain अब नए छोटे URL के लिए डिफ़ॉल्ट डोमेन है।',
        'close' => 'बंद करें',
    ],
    'dns' => [
        'option_txt' => 'विकल्प 1 — TXT रिकॉर्ड (अनुशंसित)',
        'option_cname' => 'विकल्प 2 — CNAME रिकॉर्ड',
        'type' => 'प्रकार',
        'host' => 'होस्ट',
        'value' => 'मान',
        'click_to_copy' => 'कॉपी करने के लिए क्लिक करें',
        'copied' => 'कॉपी हो गया!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. सत्यापन के दौरान प्रॉक्सी स्थिति को "DNS only" पर सेट करें।',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
