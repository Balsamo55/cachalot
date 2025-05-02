<?php
/**
 * Écrit des règles de cache statique dans le .htaccess via insert_with_markers()
 */

function cachalot_write_htaccess_cache_rules() {
    $options = cachalot_get_options();

    if (empty($options['enable_static_cache'])) {
        return;
    }

    $htaccess_path = ABSPATH . '.htaccess';

    if (!file_exists($htaccess_path) || !is_writable($htaccess_path)) {
        return;
    }

    $marker = 'CACHALOT-CACHE';

    $rules = [
    '<IfModule mod_expires.c>',
    '  ExpiresActive On',
    '  ExpiresByType image/webp "access plus 6 months"',
    '  ExpiresByType image/avif "access plus 6 months"',  // ✅ ajouté
    '  ExpiresByType image/jpeg "access plus 6 months"',
    '  ExpiresByType image/png "access plus 6 months"',
    '  ExpiresByType text/css "access plus 1 year"',
    '  ExpiresByType application/javascript "access plus 1 year"',
    '  ExpiresByType font/woff "access plus 1 year"',      // ✅ ajouté
    '  ExpiresByType font/woff2 "access plus 1 year"',     // 🔁 si jamais absent
    '</IfModule>',
    '',
    '<IfModule mod_headers.c>',
    '  <FilesMatch "\.(js|css|png|jpg|jpeg|webp|avif|woff|woff2|svg)$">',
    '    Header set Cache-Control "public, max-age=31536000, immutable"',
    '  </FilesMatch>',
    '</IfModule>'
];

    insert_with_markers($htaccess_path, $marker, $rules);
}
add_action('admin_init', 'cachalot_write_htaccess_cache_rules');
