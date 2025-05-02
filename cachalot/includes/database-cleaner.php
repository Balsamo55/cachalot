<?php
/**
 * Nettoyage des révisions et transients expirés dans la base de données
 */

// Supprime les révisions et transients expirés si activé dans les options
function cachalot_clean_database() {
    $options = cachalot_get_options();
    if ($options['clean_database']) {
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' AND option_value = ''");
    }
}
if (!wp_next_scheduled('cachalot_clean_database_cron')) {
    wp_schedule_event(time(), 'weekly', 'cachalot_clean_database_cron');
}
add_action('cachalot_clean_database_cron', 'cachalot_clean_database');