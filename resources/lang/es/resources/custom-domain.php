<?php

return [
    'fields' => [
        'domain' => 'Dominio',
        'is_wildcard' => 'Comodín',
        'is_wildcard_helper' => 'También coincide con cualquier subdominio de este dominio.',
        'is_default' => 'Predeterminado',
        'is_default_helper' => 'Los nuevos enlaces cortos usan este dominio cuando no se elige ninguno explícitamente. Solo un dominio puede ser predeterminado; activar esto lo quita de cualquier otro dominio. Debe estar verificado primero.',
        'root_redirect_url' => 'URL de Redirección Raíz',
        'status' => 'Verificado',
        'dns_record_type' => 'Registro DNS',
        'last_checked_at' => 'Última Verificación',
    ],
    'actions' => [
        'dns_instructions' => 'Instrucciones de DNS',
        'verify_now' => 'Verificar Ahora',
        'verify_queued' => 'Verificación en cola.',
        'set_default' => 'Establecer como Predeterminado',
        'set_default_success' => ':domain ahora es el dominio predeterminado para nuevos enlaces cortos.',
        'close' => 'Cerrar',
    ],
    'dns' => [
        'option_txt' => 'Opción 1 — Registro TXT (recomendado)',
        'option_cname' => 'Opción 2 — Registro CNAME',
        'type' => 'Tipo',
        'host' => 'Host',
        'value' => 'Valor',
        'click_to_copy' => 'Clic para copiar',
        'copied' => '¡Copiado!',
        'registrar_hint_cloudflare' => 'DNS > Records > Add record. Deje el proxy en "DNS only" durante la verificación.',
        'registrar_hint_godaddy' => 'My Products > DNS > Add New Record.',
        'registrar_hint_registrobr' => 'Painel de Controle > DNS > Editar Zona > adicionar registro.',
        'registrar_hint_namecheap' => 'Domain List > Manage > Advanced DNS > Add New Record.',
        'registrar_hint_hostinger' => 'Domains > DNS / Nameservers > Manage DNS records.',
    ],
];
