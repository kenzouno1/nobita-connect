<?php 
/**
 * Plugin Name:  Nobi Connect
 * Plugin URI: https://github.com/kenzouno1/nobita-connect
 * Description: Kết nối Wordpress với Nobi Pro
 * Version: 1.0.94
 * Author: Nobi.Pro
 * Author URI: https://nobi.pro
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'admin/setting-page.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/order-hook.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/lead-model.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/ninja-forms/ninja-forms.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/contact-form-hook.php';

add_action('init', function () {
    if (!isset($_COOKIE['nobi_link'])) {
        global $wp;
        $current_url = home_url($wp->request);
        setcookie('nobi_link', esc_url_raw($current_url), strtotime('+30 minutes'));
    }

    if (isset($_GET['utm_source'])) {
        setcookie('utm_source', sanitize_text_field($_GET['utm_source']), strtotime('+7 day'));
    }
    if (isset($_GET['utm_campaign'])) {
        setcookie('utm_campaign', sanitize_text_field($_GET['utm_campaign']), strtotime('+7 day'));
    }
    if (isset($_GET['utm_medium'])) {
        setcookie('utm_medium', sanitize_text_field($_GET['utm_medium']), strtotime('+7 day'));
    }
    if (isset($_GET['utm_content'])) {
        setcookie('utm_content', sanitize_text_field($_GET['utm_content']), strtotime('+7 day'));
    }
    if (isset($_GET['utm_term'])) {
        setcookie('utm_term', sanitize_text_field($_GET['utm_term']), strtotime('+7 day'));
    }
});