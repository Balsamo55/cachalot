<?php
/**
 * Gestion du chargement asynchrone des scripts et styles.
 */

// Récupère les options du plugin
$options = cachalot_get_options();

// Vérifie si le chargement asynchrone est activé
if (!empty($options['enable_async_loading'])) {
    add_filter('script_loader_tag', 'cachalot_add_async_attribute', 10, 2);
}

/**
 * Ajoute l'attribut `async` aux balises <script>.
 *
 * @param string $tag La balise <script>.
 * @param string $handle Le nom du script manipulé.
 * @return string La balise <script> modifiée avec l'attribut `async`.
 */
function cachalot_add_async_attribute($tag, $handle) {
    if (strpos($tag, 'src') !== false) {
        return str_replace(' src', ' async="async" src', $tag);
    }
    return $tag;
}