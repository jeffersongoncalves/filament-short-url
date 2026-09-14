<?php

return [
    'fields' => [
        'domain' => 'Domínio',
        'is_wildcard' => 'Curinga',
        'is_wildcard_helper' => 'Também corresponde a qualquer subdomínio deste domínio.',
        'is_default' => 'Padrão',
        'is_default_helper' => 'Novos links curtos usam este domínio quando nenhum é escolhido explicitamente. Apenas um domínio pode ser padrão; ativar isto remove o padrão de qualquer outro domínio. Precisa estar verificado primeiro.',
        'root_redirect_url' => 'URL de Redirecionamento Raiz',
        'status' => 'Verificado',
        'dns_record_type' => 'Registro DNS',
        'last_checked_at' => 'Última Verificação',
    ],
    'actions' => [
        'dns_instructions' => 'Instruções de DNS',
        'verify_now' => 'Verificar Agora',
        'verify_queued' => 'Verificação enfileirada.',
        'set_default' => 'Definir como Padrão',
        'set_default_success' => ':domain agora é o domínio padrão para novos links curtos.',
        'close' => 'Fechar',
    ],
    'dns' => [
        'option_txt' => 'Opção 1 — Registro TXT (recomendado)',
        'option_cname' => 'Opção 2 — Registro CNAME',
        'type' => 'Tipo',
        'host' => 'Host',
        'value' => 'Valor',
        'click_to_copy' => 'Clique para copiar',
        'copied' => 'Copiado!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Deixe o proxy como "DNS only" durante a verificação.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
