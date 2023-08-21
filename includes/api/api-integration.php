<?php

if (!defined('ABSPATH')) {
    exit;
}

define('BOPS_API_BASE_URL', 'https://burzi-obiavi.com/api/');

function bops_fetch_data_from_api($endpoint) {
    $api_url = BOPS_API_BASE_URL . $endpoint;
    $response = wp_remote_get($api_url);

    if (is_array($response) && !is_wp_error($response)) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        return $data;
    }

    return array(); // Return an empty array if there's an error
}

function bops_fetch_cities_from_api() {
    return bops_fetch_data_from_api('cities');
}

function bops_fetch_categories_from_api() {
    return bops_fetch_data_from_api('categories');
}

// Store cities in the database
function bops_store_cities_in_database() {
    $cities_data = bops_fetch_cities_from_api();
    update_option('bops_cities', $cities_data);
}

// Store categories in the database

function bops_store_categories_in_database() {
    $categories_data = bops_fetch_categories_from_api();
    update_option('bops_categories', $categories_data);
}