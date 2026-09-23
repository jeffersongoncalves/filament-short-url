<?php

return [
    'fields' => [
        'domain' => 'Domínio',
        'is_wildcard' => 'Wildcard',
        'is_wildcard_helper' => 'Corresponder também a qualquer subdomínio deste domínio.',
        'is_default' => 'Predefinido',
        'is_default_helper' => 'Os novos URLs curtos usam este domínio quando nenhum é escolhido explicitamente. Apenas um domínio pode ser predefinido; ativar esta opção remove-a de qualquer outro domínio. Tem de ser verificado primeiro.',
        'root_redirect_url' => 'URL de redirecionamento da raiz',
        'status' => 'Verificado',
        'dns_record_type' => 'Registo DNS',
        'last_checked_at' => 'Última verificação',
    ],
    'actions' => [
        'dns_instructions' => 'Instruções de DNS',
        'verify_now' => 'Verificar agora',
        'verify_queued' => 'Verificação colocada em fila.',
        'set_default' => 'Definir como predefinido',
        'set_default_success' => ':domain é agora o domínio predefinido para novos URLs curtos.',
        'close' => 'Fechar',
    ],
    'dns' => [
        'option_txt' => 'Opção 1 — registo TXT (recomendado)',
        'option_cname' => 'Opção 2 — registo CNAME',
        'type' => 'Tipo',
        'host' => 'Host',
        'value' => 'Valor',
        'click_to_copy' => 'Clique para copiar',
        'copied' => 'Copiado!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Defina o estado do proxy como "DNS only" durante a verificação.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
