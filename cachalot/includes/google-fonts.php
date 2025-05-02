<?php
/**
 * Gestion des Google Fonts (hébergement local ou optimisation)
 */

// Héberger les polices Google localement
function cachalot_host_google_fonts_locally() {
    $options = cachalot_get_options();
    if ($options['host_google_fonts_locally']) {
        // Désactiver les appels aux Google Fonts
        wp_dequeue_style('google-fonts');

        // Ajouter les polices hébergées localement
        wp_enqueue_style('local-google-fonts', get_stylesheet_directory_uri() . '/fonts/local-google-fonts.css');
    }
}
add_action('wp_enqueue_scripts', 'cachalot_host_google_fonts_locally', 100);