<?php
/**
 * Audit léger des ressources bloquantes pour le score PageSpeed.
 * Affiche CSS bloquants, JS sans defer, polices Google, images non WebP.
 */

function cachalot_performance_audit_panel() {
    echo '<div style="font-size: 13px; line-height: 1.5;">';

    // 1. 🔴 CSS bloquants
    global $wp_styles;
    if (!empty($wp_styles->queue)) {
        foreach ($wp_styles->queue as $handle) {
            $src = $wp_styles->registered[$handle]->src ?? '';
            if (strpos($src, 'http') === 0 && preg_match('/\.css(\?|$)/', $src)) {
                echo '🔴 CSS bloquant : <code>' . esc_html($src) . '</code><br>';
            }
        }
    }

    // 2. 🟠 JS sans defer
    global $wp_scripts;
    if (!empty($wp_scripts->queue)) {
        foreach ($wp_scripts->queue as $handle) {
            $src = $wp_scripts->registered[$handle]->src ?? '';
            if (strpos($src, 'http') === 0 && preg_match('/\.js(\?|$)/', $src)) {
                echo '🟠 JS sans defer : <code>' . esc_html($src) . '</code><br>';
            }
        }
    }

    // 3. 🔵 Polices Google détectées dans le head
    ob_start();
    do_action('wp_head');
    $head_content = ob_get_clean();
    if (strpos($head_content, 'fonts.googleapis.com') !== false) {
        preg_match_all('/https:\/\/fonts\.googleapis\.com\/[^"\']+/', $head_content, $matches);
        foreach ($matches[0] as $font_url) {
            echo '🔵 Police Google : <code>' . esc_html($font_url) . '</code><br>';
        }
    }

    // 4. 🟡 Images non WebP dans le contenu
    $post_id = get_the_ID();
    if ($post_id) {
        $content = apply_filters('the_content', get_post_field('post_content', $post_id));
        preg_match_all('/<img[^>]+src="([^"]+\.(jpg|jpeg|png))"/i', $content, $img_matches);
        if (!empty($img_matches[1])) {
            foreach ($img_matches[1] as $img) {
                echo '🟡 Image non WebP : <code>' . esc_html($img) . '</code><br>';
            }
        }
    }

    echo 'ℹ️ Ce rapport est visible uniquement sur la page du plugin Cachalot.';
    echo '</div>';
}
add_action('cachalot_admin_audit_panel', 'cachalot_performance_audit_panel');
