<?php
/**
 * Gestion de la compression Gzip et Brotli.
 */

// Vérifie si Gzip est activé sur le serveur
function cachalot_check_gzip_status() {
    $options = cachalot_get_options();
    if ($options['check_gzip_status']) {
        // Vérifie si la compression Gzip est déjà active
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
            ob_start('cachalot_gzip_compression');
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning is-dismissible">';
                echo '<p><strong>Attention :</strong> Votre serveur ne prend pas en charge la compression Gzip. Veuillez vérifier la configuration de votre serveur.</p>';
                echo '</div>';
            });
        }
    }
}

// Fonction de compression Gzip
function cachalot_gzip_compression($buffer) {
    // Vérifie que les en-têtes ne sont pas déjà envoyés
    if (!headers_sent()) {
        header("Content-Encoding: gzip");
    }
    return gzencode($buffer);
}

add_action('template_redirect', 'cachalot_check_gzip_status');