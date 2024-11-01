<?php
/*
Plugin Name: TrulyLegit | TrulyBadge
Description: Say goodbye to lost sales and missed opportunities. Our dynamic and fully functional Truly Badges are the game-changer your business needs. These badges, powered by a unique lightweight script, instill confidence in cusotmers and ensure a safe transaction experience. Stop losing sales due to customer distrust and fear - start winning customers with Truly Legit.
Version: 1.0.8
Author: Truly Legit
Author URI: http://trulylegit.com/
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Register the script injection
function truly_script_injector() {
    $site_id = get_option('truly_site_id_option');
    if(!empty($site_id)) {
		echo '<script src="https://badge.trulylegit.com/api/tlv1?siteId=' . esc_js($site_id) . '" async></script>';
    }
}
add_action('wp_footer', 'truly_script_injector');

// Create the options page
function truly_plugin_menu() {
    add_options_page('Truly Legit Configuration', 'Truly Legit', 'manage_options', 'truly', 'truly_plugin_options');
}
add_action('admin_menu', 'truly_plugin_menu');

// Options page callback
function truly_plugin_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['truly_nonce_field']) || !wp_verify_nonce($_POST['truly_nonce_field'], 'truly_legit_options_save')) {
            echo '<div class="notice notice-error"><p>Nonce verification failed.</p></div>';
            wp_die(__("Sorry, your nonce did not verify."));
        }
        
        // Save the site ID option
        if (isset($_POST['truly_site_id'])) {
            update_option('truly_site_id_option', sanitize_text_field($_POST['truly_site_id']));
        }        
    }

    // Retrieve the site ID
    $truly_site_id_value = get_option('truly_site_id_option', '');

    // Display the options form
    echo '<div class="wrap">';
    echo '<h1>Truly Legit Options</h1>';
    echo '<p>This page allows you to configure the Truly Legit plugin. Enter your Site ID below and click "Save" to update your settings.</p>';
    echo '<form method="post" action="">';
    wp_nonce_field('truly_legit_options_save', 'truly_nonce_field');
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th scope="row"><label for="truly_site_id">Site ID:</label></th>';
    echo '<td><input type="text" id="truly_site_id" name="truly_site_id" value="' . esc_attr($truly_site_id_value) . '" class="regular-text" /></td>';
    echo '</tr>';
    echo '</table>';
    echo '<p class="submit"><input type="submit" value="Save" class="button button-primary" /></p>';
    echo '</form>';

    echo '<h2>Using Shortcodes for Static Badges</h2>';
    echo '<p>For static badges, you can use the following shortcodes to place the badge on your site:</p>';
    echo '<ul>';
    echo '<li>[truly_certified_badge] - Inserts the Truly Certified badge</li>';
    echo '<li>[truly_secured_badge] - Inserts the Truly Secured badge</li>';
    echo '<li>[truly_shield_badge] - Inserts the Truly Shield badge</li>';
    echo '</ul>';
    echo '</div>';
}

// Add shortcodes
function truly_certified_badge_func() {
    return '<div id="truly-certified-badge"></div>';
}
add_shortcode('truly_certified_badge', 'truly_certified_badge_func');

function truly_secured_badge_func() {
    return '<div id="truly-secured-badge"></div>';
}
add_shortcode('truly_secured_badge', 'truly_secured_badge_func');

function truly_shield_badge_func() {
    return '<div id="truly-shield-badge"></div>';
}
add_shortcode('truly_shield_badge', 'truly_shield_badge_func');
?>
