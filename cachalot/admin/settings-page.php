<?php
/**
 * Interface utilisateur pour les options du plugin Cachalot (version améliorée avec UX)
 */

function cachalot_add_admin_menu() {
    add_options_page(
        'Options Cachalot',
        'Cachalot',
        'manage_options',
        'cachalot',
        'cachalot_options_page'
    );
}
add_action('admin_menu', 'cachalot_add_admin_menu');

function cachalot_register_settings() {
    register_setting('cachalot_options_group', CACHALOT_OPTIONS_KEY);
}
add_action('admin_init', 'cachalot_register_settings');

function cachalot_enqueue_admin_styles($hook) {
    if ($hook !== 'settings_page_cachalot') {
        return;
    }

    wp_enqueue_style(
        'cachalot-admin-style',
        plugin_dir_url(__FILE__) . 'assets/admin-style.css',
        array(),
        '1.0',
        'all'
    );
}
add_action('admin_enqueue_scripts', 'cachalot_enqueue_admin_styles');

function cachalot_options_page() {
    $options = get_option(CACHALOT_OPTIONS_KEY);
    ?>
    <div class="wrap cachalot-settings">

        <!-- Colonne gauche : réglages -->
        <div class="cachalot-column-left">
            <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/logo.svg'; ?>" alt="Cachalot Logo" class="cachalot-logo">
            <h1>Paramètres de Cachalot</h1>
            <form method="post" action="options.php">
                <?php settings_fields('cachalot_options_group'); ?>

                <!-- Section : Scripts et chargements -->
                <div class="cachalot-section">
                    <h2>Scripts et chargements</h2>
                    <p>Optimisez le chargement de vos scripts pour améliorer les performances.</p>
                    <?php echo cachalot_toggle('async_loading', 'Chargement asynchrone', $options); ?>
                    <?php echo cachalot_toggle('conditional_loading', 'Chargement conditionnel', $options); ?>
                    <?php echo cachalot_toggle('scripts_removal', 'Suppression de scripts inutiles', $options); ?>
                    <?php echo cachalot_toggle('unused_assets', 'Suppression des assets non utilisés', $options); ?>
                </div>

                <!-- Section : Nettoyage -->
                <div class="cachalot-section">
                    <h2>Nettoyage</h2>
                    <p>Gardez votre site léger en supprimant les données inutiles.</p>
                    <?php echo cachalot_toggle('database_cleaner', 'Nettoyage de la base de données', $options); ?>
                    <?php echo cachalot_toggle('heartbeat', 'Contrôle de l’API Heartbeat', $options); ?>
                </div>

                <!-- Section : Performances -->
                <div class="cachalot-section">
                    <h2>Performances</h2>
                    <p>Boostez les performances de votre site avec ces réglages.</p>
                    <?php echo cachalot_toggle('cache', 'Système de cache', $options); ?>
                    <?php echo cachalot_toggle('minification', 'Minification du code', $options); ?>
                    <?php echo cachalot_toggle('gzip_compression', 'Compression GZIP', $options); ?>
                    <?php echo cachalot_toggle('enable_static_cache', 'Optimiser le .htaccess pour les fichiers statiques', $options); ?>
                </div>

                <!-- Section : Médias et polices -->
                <div class="cachalot-section">
                    <h2>Médias et polices</h2>
                    <p>Optimisez le chargement des images et des polices pour une meilleure UX.</p>
                    <?php echo cachalot_toggle('lazy_loading', 'Lazy loading des images', $options); ?>
                    <?php echo cachalot_toggle('preload_fonts', 'Préchargement des polices', $options); ?>
                    <?php echo cachalot_toggle('google_fonts', 'Optimisation Google Fonts', $options); ?>
                </div>

                <!-- Section : Rendu initial -->
                <div class="cachalot-section">
                    <h2>Rendu initial (PageSpeed)</h2>
                    <p>Réduisez le temps de rendu et améliorez le score Core Web Vitals.</p>
                    <?php echo cachalot_toggle('enable_critical_css', 'Critical CSS inline', $options); ?>
                    <?php echo cachalot_toggle('delay_main_css', 'CSS différé (lazy)', $options); ?>
                    <?php echo cachalot_toggle('defer_scripts', 'JS non critique en "defer"', $options); ?>
                </div>

                <?php submit_button('Enregistrer les modifications'); ?>

                <p>
                    <a href="<?php echo esc_url(add_query_arg('cachalot_purge_cache', '1')); ?>" class="button button-secondary">
                        Vider le cache
                    </a>
                </p>
            </form>
        </div>

        <!-- Colonne droite : Audit -->
        <div class="cachalot-column-right">
            <h2 style="margin-top: 0;">📊 Audit PageSpeed</h2>
            <div id="cachalot-audit-results">
                <?php do_action('cachalot_admin_audit_panel'); ?>
            </div>
        </div>

    </div>
    <?php
}

function cachalot_toggle($key, $label, $options) {
    $checked = !empty($options[$key]) ? 'checked' : '';
    return "<div class='cachalot-toggle'>
                <label>$label</label>
                <label class='switch'>
                    <input type='checkbox' name='" . CACHALOT_OPTIONS_KEY . "[$key]' value='1' $checked>
                    <span class='slider round'></span>
                </label>
            </div>";
}
