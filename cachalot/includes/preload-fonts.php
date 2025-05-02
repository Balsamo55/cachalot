<?php
/**
 * Préchargement des polices externes
 */

// Précharge les polices si activé dans les options
function cachalot_preload_fonts() {
    $options = cachalot_get_options();
    if ($options['preload_fonts']) {
        echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" as="style" />';
    }
}
add_action('wp_head', 'cachalot_preload_fonts');