<?php
/**
  * Plugin Name: UTM Builder Ads Fox
  * Author: Adsfox.com
  * Author URI:https://adsfox.com/
  * Description: UTM Parameter for created short links
  * Tags: UTM Builder
  * Version: 1.0
  * License: GPLv2 or later
  * License URI: http://www.gnu.org/licenses/gpl-2.0.html

 **/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define("ADFOX_ROOT", plugin_dir_url( __FILE__ ));
define("ADFOX_ROOT_INCLUDE", plugin_dir_path(__FILE__));
define("ADFOX_AJAX", admin_url('admin-ajax.php'));


if (  is_admin() ) {

	if (!function_exists('adfox_activate')) {
		register_activation_hook ( __FILE__, 'adfox_activate' );
	  function adfox_activate() {
	  global $wpdb;
	  $create_table_query = "
	        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}adfox_utm` (
	          `id` bigint(20) NOT NULL AUTO_INCREMENT,
	          `shorturl` text NOT NULL,
	          `longurl` text NOT NULL,
						PRIMARY KEY  (id)
	        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	  ";
	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  dbDelta( $create_table_query );
	  }
	}
		// custom css and js
	if (!function_exists('adfox_enqueue')) {
		add_action('admin_enqueue_scripts', 'adfox_enqueue');
		function adfox_enqueue($hook) {

		    if ( 'toplevel_page_adfox_UTM-dashboard' != $hook ) {
		        return;
		    }

		    wp_enqueue_style('adfox_boot', plugins_url('static/bootstrap.css',__FILE__ ));
		    wp_enqueue_script('adfox_ang', plugins_url('static/angular.js',__FILE__ ));
				wp_enqueue_script('adfox_main', plugins_url('static/main.js',__FILE__ ));
		}
	}

	if ( is_file(  plugin_dir_path(__FILE__) . 'class/menu.php') ) {
			require plugin_dir_path(__FILE__) . 'class/menu.php';
	};
	if ( is_file( plugin_dir_path(__FILE__) . 'ajax/ajax.php') ) {
		require plugin_dir_path(__FILE__) . 'ajax/ajax.php';
	}

}

if(!is_admin()){
	$mt_base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
	$mt_url = $mt_base_url . $_SERVER["REQUEST_URI"];

	global $wpdb;
	$wpdb_prefix = $wpdb->prefix;
	$wpdb_tablename = $wpdb_prefix.'adfox_utm';
	$result = $wpdb->get_results('SELECT longurl FROM '. $wpdb_tablename . ' WHERE shorturl="'.$mt_url.'"');
	if(count($result) !== 0){
		header('Location: '.$result[0]->longurl);
		exit;
	}

}
