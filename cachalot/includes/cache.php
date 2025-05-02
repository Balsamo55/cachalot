<?php
/**
 * Gestion du cache des pages
 */

// Servir une page en cache si elle existe
function cachalot_cache_serve() {
    $options = cachalot_get_options();
    if ($options['enable_cache']) {
        $cache_file = CACHALOT_CACHE_DIR . md5($_SERVER['REQUEST_URI']) . '.html';
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 3600) {
            echo file_get_contents($cache_file);
            exit;
        }
    }
}
add_action('init', 'cachalot_cache_serve');

// Stocker une page dans le cache
function cachalot_cache_start() {
    $options = cachalot_get_options();
    if ($options['enable_cache'] && !is_user_logged_in() && !defined('DONOTCACHEPAGE')) {
        ob_start('cachalot_cache_store');
    }
}
add_action('template_redirect', 'cachalot_cache_start');

function cachalot_cache_store($buffer) {
    $cache_file = CACHALOT_CACHE_DIR . md5($_SERVER['REQUEST_URI']) . '.html';
    if (!is_dir(dirname($cache_file))) {
        mkdir(dirname($cache_file), 0755, true);
    }
    file_put_contents($cache_file, $buffer);
    return $buffer;
}

// Ajouter une fonction pour purger tout le cache
function cachalot_purge_cache() {
    $files = glob(CACHALOT_CACHE_DIR . '*.html');
    if ($files) {
        array_map('unlink', $files);
    }
}