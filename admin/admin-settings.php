<?php

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue admin styles
function bops_enqueue_admin_styles()
{
    wp_enqueue_style('bops-admin-styles', plugin_dir_url(__FILE__) . 'assets/css/admin-styles.css');
}
add_action('admin_enqueue_scripts', 'bops_enqueue_admin_styles');

// Enqueue admin scripts
function bops_enqueue_admin_scripts()
{
    wp_enqueue_script('bops-admin-scripts', plugin_dir_url(__FILE__) . 'assets/js/admin-scripts.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'bops_enqueue_admin_scripts');


// Add menu item to the admin menu
function bops_add_admin_menu()
{
    add_menu_page(
        'Burzi-Obyavi Product Sync',
        'Burzi-Obyavi Sync',
        'manage_options',
        'bops-settings',
        'bops_settings_page'
    );
}

add_action('admin_menu', 'bops_add_admin_menu');

// admin/admin-settings.php

function bops_settings_page()
{

    $cities = get_option('bops_cities', array());
    $woocommerce_categories = bops_get_woocommerce_categories();
    $burzi_obiavi_categories = get_option('bops_categories', array());
    $show_fields = current_user_can('manage_options') && get_option('bops_username') && get_option('bops_password');


    ?>
    <div class="wrap">
        <h2>Burzi-Obyavi Product Sync Settings</h2>
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php
            settings_fields('bops_settings_group');
            do_settings_sections('bops-settings');
            submit_button();
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Username</th>
                    <td>
                        <input type="text" name="bops_username"
                            value="<?php echo esc_attr(get_option('bops_username')); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Password</th>
                    <td>
                        <?php wp_nonce_field('bops_password_nonce', 'bops_password_nonce'); ?>
                        <input type="password" name="bops_password" value="" />
                    </td>
                </tr>


                    <tr>
                        <th scope="row">City</th>
                        <td>
                            <select name="bops_city">
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo esc_attr($city['id']); ?>"><?php echo esc_html($city['text']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Category Mapping</th>
                        <td>
                            <?php foreach ($woocommerce_categories as $woocommerce_category): ?>
                                <p>
                                    <?php echo esc_html($woocommerce_category->name); ?>
                                    <select name="bops_category_mapping[<?php echo esc_attr($woocommerce_category->term_id); ?>]">
                                        <option value="">Select Burzi-Obyavi Category</option>
                                        <?php foreach ($burzi_obiavi_categories as $burzi_category): ?>
                                            <?php if (!empty($burzi_category['subcategories'])): ?>
                                                <optgroup label="<?php echo esc_html($burzi_category['name']); ?>">
                                                    <?php foreach ($burzi_category['subcategories'] as $sub_category): ?>
                                                        <option value="<?php echo esc_attr($sub_category['id']); ?>">
                                                            <?php echo esc_html($sub_category['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php else: ?>
                                                <option value="<?php echo esc_attr($burzi_category['id']); ?>">
                                                    <?php echo esc_html($burzi_category['name']); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </p>
                            <?php endforeach; ?>
                        </td>
                    </tr>
            </table>
        </form>
    </div>
    <?php
}