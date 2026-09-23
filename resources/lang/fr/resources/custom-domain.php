<?php

return [
    'fields' => [
        'domain' => 'Domaine',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Correspond aussi à tout sous-domaine de ce domaine.',
        'is_default' => 'Par défaut',
        'is_default_helper' => 'Les nouvelles URL courtes utilisent ce domaine lorsqu\'aucun n\'est choisi explicitement. Un seul domaine peut être par défaut ; activer cette option la retire des autres domaines. Il doit d\'abord être vérifié.',
        'root_redirect_url' => 'URL de redirection racine',
        'status' => 'Vérifié',
        'dns_record_type' => 'Enregistrement DNS',
        'last_checked_at' => 'Dernière vérification',
    ],
    'actions' => [
        'dns_instructions' => 'Instructions DNS',
        'verify_now' => 'Vérifier maintenant',
        'verify_queued' => 'Vérification mise en file d\'attente.',
        'set_default' => 'Définir par défaut',
        'set_default_success' => ':domain est désormais le domaine par défaut des nouvelles URL courtes.',
        'close' => 'Fermer',
    ],
    'dns' => [
        'option_txt' => 'Option 1 — enregistrement TXT (recommandé)',
        'option_cname' => 'Option 2 — enregistrement CNAME',
        'type' => 'Type',
        'host' => 'Hôte',
        'value' => 'Valeur',
        'click_to_copy' => 'Cliquez pour copier',
        'copied' => 'Copié !',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Réglez le statut du proxy sur "DNS only" pendant la vérification.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
