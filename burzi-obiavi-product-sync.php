<?php
/**
 * Plugin Name: Burzi-Obiavi Product Sync
 * Description: Sync products between WooCommerce and Burzi-Obiavi.
 * Version: 1.0.0
 * Author: Burzi Obiavi
 * Author URI: burzi-obiavi.com
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'admin/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/api/api-integration.php';
require_once plugin_dir_path(__FILE__) . 'includes/woocommerce/woocommerce-integration.php';

 
// Activation hook
function bops_activate() {

    bops_store_cities_in_database();
    bops_store_categories_in_database();

}
register_activation_hook(__FILE__, 'bops_activate');

// Deactivation hook
function bops_deactivate() {

}
register_deactivation_hook(__FILE__, 'bops_deactivate');

function bops_settings_init() {
    register_setting('bops_settings_group', 'bops_username', 'bops_validate_username');
    register_setting('bops_settings_group', 'bops_password', 'bops_validate_password');
    register_setting('bops_settings_group', 'bops_city', 'bops_validate_city');
    

}

add_action('admin_init', 'bops_settings_init');


function sanitize_callback($input) {
    return sanitize_text_field($input);
}

function bops_validate_username($username) {
    if (empty($username)) {
        add_settings_error('bops_username', 'bops_username_empty', 'Username is required.');
    }
    return $username;
}

// Validate password (nonce validation)
function bops_validate_password($password) {
    if (!isset($_POST['bops_password_nonce']) || !wp_verify_nonce($_POST['bops_password_nonce'], 'bops_password_nonce')) {
        add_settings_error('bops_password', 'bops_password_nonce_error', 'Invalid security nonce.');
        return '';
    }
    return $password;
}

// Validate city (optional, based on your requirements)
function bops_validate_city($city) {
    if (empty($city)) {
        add_settings_error('bops_city', 'bops_city_empty', 'City is required.');
    }
    return $city;
}
