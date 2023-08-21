<?php

if (!defined('ABSPATH')) {
    exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (!is_plugin_active('woocommerce/woocommerce.php')) {
    return;
}

function bops_get_woocommerce_categories() {
    $args = array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    );

    $categories = get_terms($args);

    $categories = array_filter($categories, function($category) {
        return $category->name !== 'Uncategorized';
    });

    return $categories;
}
