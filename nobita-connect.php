<?php 
/**
 * Plugin Name:  Nobita Connect
 * Plugin URI: https://nobita.pro
 * Description: Kết nối Wordpress với Nobita
 * Version: 1.0.0
 * Author: Nobita
 * Author URI:https://github.com/kenzouno1
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'admin/setting-page.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/order-hook.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/lead-model.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/contact-form-hook.php';