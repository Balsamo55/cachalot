<?php
/**
 * Gestion du lazy loading des images
 */

// Active le lazy loading des images dans le contenu si activé dans les options
function cachalot_lazy_load_images($content) {
    $options = cachalot_get_options();
    if ($options['enable_lazy_load'] && !is_feed() && !is_preview()) {
        return preg_replace_callback('/<img([^>]+)>/', function ($matches) {
            return str_replace('<img', '<img loading="lazy"', $matches[0]);
        }, $content);
    }
    return $content;
}
add_filter('the_content', 'cachalot_lazy_load_images');