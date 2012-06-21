<?php
/*
Plugin Name: PHP Image Editor Lite
Plugin URI: http://www.phpimageeditor.se/wordpress.php
Description: Alternative image editing directly in WordPress.
Author: Patrik Hultgren
Author URI: http://www.phpimageeditor.se
Version: 0.5
Stable tag: 0.5
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * PHP Image Editor Lite
 *
 * @package WordPress
 *
 * Alternative image editing directly in WordPress.
 *
 * @since 2012-06-02
 */
 
/**
* Protection
*
* This string of code will prevent hacks from accessing the file directly.
*/
defined('ABSPATH') or die("Cannot access pages directly.");

define('PIE_VERSION', '0.5');
define('PIE_USER_CAPABILITY', 'upload_files');

 function phpimageeditorlite_init(){
    
 	if (is_admin() && current_user_can(PIE_USER_CAPABILITY)) {

 		wp_enqueue_script('jquery');
	    wp_enqueue_script('thickbox',null,array('jquery'));
	    wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');
	    wp_enqueue_script('phpimageeditorlite-script', plugins_url().'/phpimageeditorlite/editimage.js', array('jquery', 'thickbox'));
	    
		$params = array(
		  'host' => site_url(),
		  'language' => get_bloginfo("language"),
		  'wordpressversion' => get_bloginfo("version"),
		  'version' => PIE_VERSION
		);    
	
	 	wp_localize_script('phpimageeditorlite-script', 'PieParams', $params);
 	}
}

add_action('init','phpimageeditorlite_init');

function phpimageeditorlite_attachment_post_meta($meta_value) {
	
	global $wpdb;
	$result1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s", $meta_value));
	
	if (count($result1)) {
		$postmeta = $result1[0];
		
		$result2 = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_wp_attachment_metadata' AND post_id = %d", $postmeta->post_id));
		if (count($result2)) {
			
			$postmeta2 = new stdClass;
			$postmeta2->post_id = $result2[0]->post_id;
			$postmeta2->metadata = unserialize($result2[0]->meta_value);

			return $postmeta2;
		}
	}
		
	return false;
}

function phpimageeditorlite_parse_request($wp) {
	
	if (current_user_can(PIE_USER_CAPABILITY) && array_key_exists('pie-lite', $wp->query_vars))
		include plugin_dir_path(__FILE__).'index.php';
}
	
add_action('parse_request', 'phpimageeditorlite_parse_request');

function phpimageeditorlite_query_vars($vars) {

	if (current_user_can(PIE_USER_CAPABILITY))
		$vars[] = 'pie-lite';
	
	return $vars;
}

add_filter('query_vars', 'phpimageeditorlite_query_vars');