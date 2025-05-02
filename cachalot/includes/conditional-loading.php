<?php
/**
 * Chargement conditionnel des scripts
 */

// Charge certains scripts uniquement sur les pages nécessaires
function cachalot_conditional_script_loading() {
    $options = cachalot_get_options();
    if ($options['enable_conditional_loading']) {
        // Exemple : Charger un script uniquement sur les pages de formulaire
        if (!is_page(['contact', 'formulaire'])) {
            wp_dequeue_script('form-script-handle');
        }

        // Exemple : Charger un script uniquement sur la page d'accueil
        if (!is_front_page()) {
            wp_dequeue_script('homepage-script-handle');
        }
    }
}
add_action('wp_enqueue_scripts', 'cachalot_conditional_script_loading', 100);