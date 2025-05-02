<?php
/**
 * Gestion de la minification des fichiers HTML, CSS et JS
 */

// Active la minification globale si activée dans les options
function cachalot_enable_minification() {
    $options = cachalot_get_options();
    if ($options['enable_minification'] && !is_admin()) {
        ob_start('cachalot_minify_html');
    }
}
add_action('init', 'cachalot_enable_minification');

// Fonction principale pour minifier le HTML
function cachalot_minify_html($buffer) {
    return preg_replace(['/>\s+</', '/\s+/'], ['><', ' '], $buffer);
}