<?php
/*
Plugin Name: Cachalot
Description: Plugin ultraléger pour améliorer les performances WordPress avec une mise en cache efficace et des optimisations. Compatible avec Divi et Elementor.
Version: 1.0
Author: Les Idées Fixes
Text Domain: cachalot
*/

// === Définition des constantes ===
define('CACHALOT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CACHALOT_CACHE_DIR', WP_CONTENT_DIR . '/cache/cachalot/');
define('CACHALOT_OPTIONS_KEY', 'cachalot_options');

// === Chargement sécurisé des modules ===
$includes_dir = CACHALOT_PLUGIN_DIR . 'includes/';

// 1. Charger helpers.php en premier (contient cachalot_get_options())
require_once $includes_dir . 'helpers.php';

// 2. Charger tous les autres fichiers PHP, sauf helpers.php déjà inclus
foreach (glob($includes_dir . '*.php') as $file) {
    if (basename($file) !== 'helpers.php' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        require_once $file;
    }
}

// === Chargement des fichiers admin ===
require_once CACHALOT_PLUGIN_DIR . 'admin/settings-page.php';

// === Gestion de la purge du cache via l'URL ===
function cachalot_handle_purge_cache() {
    if (isset($_GET['cachalot_purge_cache']) && $_GET['cachalot_purge_cache'] === '1') {
        // ⚠️ Recommande d’ajouter une vérification nonce ici pour plus de sécurité
        cachalot_purge_cache();
        wp_safe_redirect(remove_query_arg('cachalot_purge_cache') . '&cache-purged=1');
        exit;
    }
}
add_action('init', 'cachalot_handle_purge_cache');
