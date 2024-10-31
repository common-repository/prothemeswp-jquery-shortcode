<?php
/*
Plugin Name: ProThemesWP jQuery Shortcode
Plugin URI: https://prothemeswp.com/tutorials/how-to-add-javascript-and-jquery-to-wordpress-posts
Description: Add inline javascript or jQuery to your posts and pages using a shortcode.
Author: ProThemesWP
Author URI: https://prothemeswp.com
Text Domain: prothemeswp-jquery-shortcode
Domain Path: /languages/
Version: 1.1
Copyright: Copyright (c) 2019, ProThemesWP - info@prothemeswp.com
@copyright Copyright (c) 2019, ProThemesWP - info@prothemeswp.com
*/

$prothemeswp_jquery_shortcode_counter = 0;
$prothemeswp_jquery_shortcode_javascripts = array();


//Add support and reviews links
add_filter( 'plugin_row_meta', function( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if( plugin_basename( __FILE__ ) == $plugin_file ) {
			$plugin_meta[] = '<a href="https://wordpress.org/support/plugin/' . dirname( plugin_basename( __FILE__ ) ) . '/">' . __( 'Support', 'prothemeswp-jquery-shortcode' ) . '</a>';
			$plugin_meta[] = '<a href="https://wordpress.org/support/plugin/' . dirname( plugin_basename( __FILE__ ) ) . '/reviews/">' . __( 'Reviews', 'prothemeswp-jquery-shortcode' ) . '</a>';
		}
		return $plugin_meta;	
}, 10, 4 );

//Prevent WordPress texturizing the content of the shortcodes
add_filter( 'no_texturize_shortcodes', 'prothemeswp_jquery_shortcode_no_texturize_shortcodes' );

if(!function_exists( 'prothemeswp_jquery_shortcode_no_texturize_shortcodes' )) {
	
	function prothemeswp_jquery_shortcode_no_texturize_shortcodes( $shortcodes ) {
		$shortcodes[] = 'jquery';
		$shortcodes[] = 'prothemeswpjquery';
		return $shortcodes;
	}
	
}

//The shortcode
if( !function_exists( 'prothemeswp_jquery_shortcode' ) ) {
	
	function prothemeswp_jquery_shortcode( $atts, $content ) {
		global $prothemeswp_jquery_shortcode_javascripts;
		global $prothemeswp_jquery_shortcode_counter;
		$content = wp_kses_post( str_replace( '<br />', '', $content ) );
		$content = wp_kses_post( str_replace( '<p>', '', $content ) );
		$content = wp_kses_post( str_replace( '</p>', '', $content ) );
		$content = preg_replace( '/\n/',' ', $content);
		$prothemeswp_jquery_shortcode_javascripts[$prothemeswp_jquery_shortcode_counter] = $content;
		$prothemeswp_jquery_shortcode_counter++;
	}
	
}

//Scripts
add_action( 'wp_footer', 'prothemeswp_jquery_shortcode_scripts' );

function prothemeswp_jquery_shortcode_scripts() {
	global $prothemeswp_jquery_shortcode_javascripts;
	wp_enqueue_script( 'prothemeswp-jquery-shortcode-frontend', plugins_url( 'prothemeswp-jquery-shortcode-frontend.js', __FILE__ ) );
	wp_localize_script( 'prothemeswp-jquery-shortcode-frontend', 'prothemeswpjQueryShortcode',
		array( 'javascripts' => $prothemeswp_jquery_shortcode_javascripts ) );
}
		


//Make sure jquery is loaded in the header
add_action( 'wp_enqueue_scripts', 'prothemeswp_jquery_shortcode_enqueue_scripts' );

if( ! function_exists('prothemeswp_jquery_shortcode_enqueue_scripts' ) ) {
	function prothemeswp_jquery_shortcode_enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
	}
}


//Make plugin translatable
add_action( 'plugins_loaded', 'prothemeswp_fade_in_shortcode_load_textdomain' );

if( !function_exists( 'prothemeswp_fade_in_shortcode_load_textdomain' ) ) {

	function prothemeswp_fade_in_shortcode_load_textdomain() {
	  load_plugin_textdomain( 'prothemeswp-fade-in-shortcode', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	}

}

//In case there is another plugin with the [jquery] shortcode
if( !function_exists( 'prothemeswp_jquery_shortcode_registered_notice' ) ) {
	
	function prothemeswp_jquery_shortcode_registered_notice() {
		echo '<div class="notice notice-info is-dismissible">
			  <p>' . __( 'You have more than one plugin that uses the [jquery] shortcode. Please use the shortcode [prothemeswpjquery] instead of [jquery] to use ProThemesWP jQuery Shortcode\'s.',
				'prothemeswp-jquery-shortcode' ) . '</p>
			 </div>';
	}
	
}

//Add shortcode(s)
if( !function_exists( 'prothemeswp_jquery_shortcode_plugins_loaded' ) ) {
	
	function prothemeswp_jquery_shortcode_plugins_loaded() {
		global $prothemeswp_jquery_shortcodes;
		if( shortcode_exists( 'jquery' ) ) {
			add_action( 'admin_notices', 'prothemeswp_jquery_shortcode_registered_notice' );
			$prothemeswp_jquery_shortcodes = array( 'prothemeswpjquery' );
		} else {
			add_shortcode( 'jquery', 'prothemeswp_jquery_shortcode' );
			$prothemeswp_jquery_shortcodes = array( 'jquery', 'prothemeswpjquery' );
		}
		add_shortcode( 'prothemeswpjquery', 'prothemeswp_jquery_shortcode' );
	}
	
}
add_action( 'plugins_loaded', 'prothemeswp_jquery_shortcode_plugins_loaded' );

if( !function_exists( 'prothemeswp_jquery_shortcode_admin_enqueue_scripts' ) ) {

	function prothemeswp_jquery_shortcode_admin_enqueue_scripts() {
		global $prothemeswp_jquery_shortcodes;
		wp_enqueue_script( 'prothemeswp-jquery-shortcode-admin', plugins_url( 'prothemeswp-jquery-shortcode-admin.js', __FILE__ ) );
		wp_localize_script( 'prothemeswp-jquery-shortcode-admin', 'prothemeswpjQueryShortcode', array( 'shortcodes' => $prothemeswp_jquery_shortcodes ) );
	}
	
}

add_action( 'admin_enqueue_scripts', 'prothemeswp_jquery_shortcode_admin_enqueue_scripts' );

//Adds a tinymce plugin.
if(!function_exists('prothemeswp_jquery_shortcode_mce_external_plugins')) {
	
	function prothemeswp_jquery_shortcode_mce_external_plugins($plugin_array) {
		$plugin_array['prothemeswp-jquery-shortcode'] = plugins_url('prothemeswp-jquery-shortcode-tinymce.js', __FILE__);
		return $plugin_array;
	}
	
}

add_filter( 'mce_external_plugins', 'prothemeswp_jquery_shortcode_mce_external_plugins' );