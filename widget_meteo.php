<?php

/**
 * @package Widget_Meteo
 * @version 1.0.0
 */
/*
Plugin Name: Widget Météo
Plugin URI: http://wordpress.org/plugins/widget-meteo/
Description: Un super plug-in météo pour donner... la météo ! Incroyable !
Author: Raphaël Granizo y Oliver
Version: 1.0.0
Author URI: https://raphaelgyo.github.io/
*/

// Fonction pour le CSS de la page du plugin
function load_plugin_css()
{
    wp_enqueue_style('style_widget_meteo', plugin_dir_url(__FILE__) . 'style_widget_meteo.css', array(), '1.0.0', 'all');
}
add_action('admin_enqueue_scripts', 'load_plugin_css');

// Fonction pour le js de la page du plugin
function load_plugin_js()
{
    wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'script.js', array(), '1.0.0', 'all');

    $input_value = get_option('widget_meteo_settings');
    $key = $input_value['API_key'];
    $idcity = $input_value['id_city'];
    $widgetchoice = $input_value['widget_choice'];
    $value = [
        'key' => $key,
        'idcity' => $idcity,
        'widgetchoice' => $widgetchoice,
    ];

    wp_localize_script('script', 'value', $value);
}

add_action('admin_enqueue_scripts', 'load_plugin_js');
add_action('wp_enqueue_scripts', 'load_plugin_js');

// In plugin main file
add_action('admin_menu', 'widget_meteo_menu_items');
/**
 * Registers our new menu item
 */
function widget_meteo_menu_items()
{
    // Create a top-level menu item.
    $hookname = add_menu_page(
        'Bonjour,',                                             // Page title - Titre de la page
        'Widget Météo',                                         // Menu title - Titre du menu
        'manage_options',                                       // Capabilities - Capacités
        'widget_meteo',                                         // Slug
        'widget_meteo_markup',                                  // Display callback
        'dashicons-cloud',                                    // Icon
        // 'dashicons-tickets',                                    // Icon
        66                                                      // Priority/position. Just after 'Plugins'
    );
}

// Ajout d'un groupe de settings fields
add_action('admin_init', 'widget_meteo_settings');
/**
 * Registers a single setting
 */
function widget_meteo_settings()
{
    register_setting(
        'widget_meteo_settings',                    // Settings group.
        'widget_meteo_settings',                    // Setting name
        'sanitize'                                  // Sanitize callback.
    );

    // Register a section to house our widget meteo setting
    add_settings_section(
        'widget_meteo_section',                     // Section ID
        'Bienvenue sur votre plugin de météo.',     // Title
        'widget_meteo_section_markup',              // Callback or empty string
        'widget_meteo_settings_page'                // Page to display the section in.
    );

    // Register the first field
    add_settings_field(
        'API_key_field',                            // Field ID
        'Entrez votre clé API',                     // Title
        'API_key_field_markup',                     // Callback to display the field
        'widget_meteo_settings_page',               // Page
        'widget_meteo_section'                      // Section
    );

    // Register the second field
    add_settings_field(
        'id_city_field',
        'Entrez l\'id de la ville',
        'id_city_field_markup',
        'widget_meteo_settings_page',
        'widget_meteo_section'
    );

    // Register the third field
    add_settings_field(
        'widget_choice_field',
        'Choisissez votre widget',
        'widget_choice_field_markup',
        'widget_meteo_settings_page',
        'widget_meteo_section'
    );

    /**
     * Displays our setting field
     * 
     * @param  array  $args  Arguments passed to corresponding add_settings_field() call
     */

    function API_key_field_markup($args)
    {
        $setting = get_option('widget_meteo_settings');
        $value   = $setting['API_key'] ?: '';
?>
        <input class="regular-text" type="text" name="widget_meteo_settings[API_key]" value="<?= esc_attr($value); ?>">

    <?php
    };

    function id_city_field_markup($args)
    {
        $setting = get_option('widget_meteo_settings');
        $value   = $setting['id_city'] ?: '';
    ?>
        <input class="regular-text" type="text" name="widget_meteo_settings[id_city]" value="<?= esc_attr($value); ?>">

    <?php
    };

    function widget_choice_field_markup($args)
    {
        $setting = get_option('widget_meteo_settings');
        $value   = $setting['widget_choice'] ?: '';
    ?>
        <input class="regular-text" type="text" name="widget_meteo_settings[widget_choice]" value="<?= esc_attr($value); ?>">

    <?php
    };

    function widget_meteo_section_markup($args)
    {
    };
}

/**
 * Markup callback for the settings page / Rappel de balisage pour la page des paramètres
 */
function widget_meteo_markup()
{
    ?>
    <div class="wrap">
        <!-- Affiche le titre de la page -->
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="POST">
            <?php
            settings_fields('widget_meteo_settings');
            do_settings_sections('widget_meteo_settings_page');
            // Affiche le bouton submit
            submit_button();
            ?>
        </form>
    </div>
    <div id="openweathermap-widget"></div>
<?php
}

add_shortcode('widget_meteo', 'meteo_shortcode');
function meteo_shortcode()
{
    $div = '<div id="openweathermap-widget"></div>';
    return $div;
}
