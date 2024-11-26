<?php 
/**
 * Plugin Name:  Nobita Connect
 * Plugin URI: https://github.com/kenzouno1/nobita-connect
 * Description: Kết nối Wordpress với Nobita
 * Version: 1.0.93
 * Author: Nobita
 * Author URI: https://nobita.pro
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'admin/setting-page.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/order-hook.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/lead-model.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/ninja-forms/ninja-forms.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/contact-form-hook.php';