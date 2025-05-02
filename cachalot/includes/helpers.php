<?php
/**
 * Fichier des fonctions utilitaires pour le plugin Cachalot.
 */

// Récupère les options du plugin, avec des valeurs par défaut si elles n'existent pas
if (!function_exists('cachalot_get_options')) {
    /**
     * Récupère les options du plugin Cachalot.
     *
     * @return array Les options du plugin avec des valeurs par défaut.
     */
    function cachalot_get_options() {
       $defaults = [
    'enable_cache' => false,
    'enable_minification' => false,
    'enable_lazy_load' => false,
    'check_gzip_status' => false,
    'control_heartbeat' => false,
    'clean_database' => false,
    'clean_headers' => false,
    'remove_unused_scripts' => false,
    'preload_fonts' => false,
    'enable_async_loading' => false,
    'enable_conditional_loading' => false,
    'host_google_fonts_locally' => false,
    'remove_unused_assets' => false,
    'enable_critical_css' => false,
    'delay_main_css' => false,
    'defer_scripts' => false,
    'enable_static_cache' => false,
];

        $options = get_option(CACHALOT_OPTIONS_KEY, $defaults);

        // Fusionne les options existantes avec les valeurs par défaut
        return wp_parse_args($options, $defaults);
    }
}