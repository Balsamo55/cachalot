<?php
/**
 * Suppression des CSS et JS inutilisés
 */

// Désactive les styles et scripts inutiles sur certaines pages
function cachalot_remove_unused_assets() {
    $options = cachalot_get_options();
    if ($options['remove_unused_assets']) {
        // Exemple : Désactiver les émoticônes (Emoji)
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // Exemple : Désactiver oEmbed
        remove_action('wp_head', 'wp_oembed_add_host_js');

        // Ajoutez ici les styles/scripts spécifiques que vous souhaitez désactiver
        wp_dequeue_style('wp-block-library'); // Styles des blocs Gutenberg
        wp_dequeue_style('classic-theme-styles'); // Styles classiques
    }
}
add_action('wp_enqueue_scripts', 'cachalot_remove_unused_assets', 100);