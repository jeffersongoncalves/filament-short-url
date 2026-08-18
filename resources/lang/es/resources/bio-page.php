<?php

return [
    'fields' => [
        'handle' => 'Identificador',
        'handle_helper' => 'URL pública: /bio/{handle}',
        'title' => 'Título',
        'bio' => 'Bio',
        'avatar' => 'Avatar',
        'theme' => 'Tema',
        'theme_helper' => 'Almacenado para uso futuro — la página pública actualmente se renderiza con un único tema oscuro integrado, independientemente de este valor.',
        'is_published' => 'Publicado',
        'seo_section' => 'SEO / Open Graph',
        'og_title' => 'Título OG',
        'og_description' => 'Descripción OG',
        'og_image' => 'Imagen OG',
        'blocks_section' => 'Bloques',
        'blocks' => 'Bloques',
        'block_type' => 'Tipo',
        'block_label' => 'Etiqueta',
        'block_url' => 'URL',
        'block_body' => 'Texto',
        'block_enabled' => 'Activado',
        'total_views' => 'Vistas',
    ],
    'themes' => [
        'default' => 'Predeterminado (oscuro)',
        'light' => 'Claro',
        'sunset' => 'Atardecer',
        'ocean' => 'Océano',
    ],
    'block_types' => [
        'link' => 'Enlace',
        'text' => 'Texto',
        'image' => 'Imagen',
        'video' => 'Video',
    ],
    'actions' => [
        'preview' => 'Vista Previa',
    ],
    'analytics' => [
        'heading' => 'Clics por Bloque',
        'clicks' => 'Clics',
    ],
];
