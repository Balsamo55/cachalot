<?php
/**
 * Suppression des scripts inutiles (émoticônes, oEmbed, etc.)
 */

// Supprime les scripts inutiles si activé dans les options
function cachalot_remove_unnecessary_scripts() {
    $options = cachalot_get_options();
    if ($options['remove_unused_scripts']) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }
}
add_action('init', 'cachalot_remove_unnecessary_scripts');