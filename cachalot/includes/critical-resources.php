<?php
/**
 * Optimisation du rendu initial : Critical CSS et defer/async JS
 */

// 0. Préconnecter et précharger les Google Fonts
function cachalot_preconnect_fonts() {
    $options = cachalot_get_options();

    if (!empty($options['enable_critical_css'])) {
        echo "\n<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">";
        echo "\n<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>";
        echo "\n<link rel=\"preload\" as=\"style\" href=\"https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap\">";
    }
}
add_action('wp_head', 'cachalot_preconnect_fonts', 0);

// 1. Inliner le CSS critique
function cachalot_inline_critical_css() {
    $options = cachalot_get_options();
    if (!empty($options['enable_critical_css'])) {
        $path = get_stylesheet_directory() . '/critical.css';
        if (file_exists($path)) {
            echo '<style>' . file_get_contents($path) . '</style>';
        }
    }
}
add_action('wp_head', 'cachalot_inline_critical_css', 1);

// 2. Reporter le chargement du CSS non critique
function cachalot_delay_main_css() {
    $options = cachalot_get_options();
    if (!empty($options['delay_main_css'])) {
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = "<?php echo esc_url(get_stylesheet_uri()); ?>";
            document.head.appendChild(link);
        });
        </script>
        <noscript><link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri()); ?>"></noscript>
        <?php
    }
}
add_action('wp_footer', 'cachalot_delay_main_css');

// 3. Ajouter l’attribut defer à certains scripts
function cachalot_defer_non_critical_scripts($tag, $handle) {
    $options = cachalot_get_options();

    // 🔍 Log temporaire de tous les scripts chargés (désactivable après debug)
    // error_log('Cachalot loaded script handle: ' . $handle);

    if (!empty($options['defer_scripts'])) {
        $defer_handles = [
            // Commun
            'jquery-ui',
            'wp-polyfill',
            'comment-reply',

            // Formulaires / sécurité
            'contact-form-7',
            'et-recaptcha-v3',
            'et-core-api-spam-recaptcha',

            // Divi & DiviFlash
            'divi-custom-script',
            'df-menu-ext-script',
            'diviflash-frontend-bundle',
            'df-imagegallery-lib',
            'df-rangeSlider',
            'df_cpt_filter',
            'df-cpt-carousel',

            // jQuery extensions
            'jquery-core',
            'jquery-migrate',
            'jquery-mobile',

            // UI et animations
            'fitvids',
            'magnific-popup',
            'easypiechart',
            'salvattore',
            'et-core-common',
            'et-builder-modules-script-motion',
            'et-builder-modules-script-sticky',

            // Vidéo / media
            'imageload',
            'mediaelement-core',
            'mediaelement-migrate',
            'wp-mediaelement',

            // Elementor (prévisionnel)
            'elementor-frontend',
            'elementor-waypoints'
        ];

        if (in_array($handle, $defer_handles)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
    }
    return $tag;
}
add_filter('script_loader_tag', 'cachalot_defer_non_critical_scripts', 10, 2);
