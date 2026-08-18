<?php

return [
    'fields' => [
        'handle' => 'Identificador',
        'handle_helper' => 'URL pública: /bio/{handle}',
        'title' => 'Título',
        'bio' => 'Bio',
        'avatar' => 'Avatar',
        'theme' => 'Tema',
        'theme_helper' => 'Armazenado para uso futuro — a página pública atualmente renderiza com um único tema escuro embutido, independente deste valor.',
        'is_published' => 'Publicado',
        'seo_section' => 'SEO / Open Graph',
        'og_title' => 'Título OG',
        'og_description' => 'Descrição OG',
        'og_image' => 'Imagem OG',
        'blocks_section' => 'Blocos',
        'blocks' => 'Blocos',
        'block_type' => 'Tipo',
        'block_label' => 'Rótulo',
        'block_url' => 'URL',
        'block_body' => 'Texto',
        'block_enabled' => 'Ativado',
        'total_views' => 'Visualizações',
    ],
    'themes' => [
        'default' => 'Padrão (escuro)',
        'light' => 'Claro',
        'sunset' => 'Pôr do Sol',
        'ocean' => 'Oceano',
    ],
    'block_types' => [
        'link' => 'Link',
        'text' => 'Texto',
        'image' => 'Imagem',
        'video' => 'Vídeo',
    ],
    'actions' => [
        'preview' => 'Pré-visualizar',
    ],
    'analytics' => [
        'heading' => 'Cliques por Bloco',
        'clicks' => 'Cliques',
    ],
];
