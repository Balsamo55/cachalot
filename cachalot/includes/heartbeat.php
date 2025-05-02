<?php
/**
 * Contrôle de la fréquence de la Heartbeat API
 */

// Modifie la fréquence de la Heartbeat API si activée dans les options
function cachalot_control_heartbeat($settings) {
    $options = cachalot_get_options();
    if ($options['control_heartbeat']) {
        $settings['interval'] = 60; // Réduit la fréquence à 1 requête par minute
    }
    return $settings;
}
add_filter('heartbeat_settings', 'cachalot_control_heartbeat');