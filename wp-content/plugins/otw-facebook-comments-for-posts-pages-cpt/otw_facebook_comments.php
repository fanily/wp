<?php
/**
 * Plugin Name: OTW Facebook Comments for Posts, Pages, CPT
 * Plugin URI: http://OTWthemes.com 
 * Description: Add Facebook Comments in your Posts, Pages and Custom Post Types
 * Author: OTWthemes.com
 * Version: 1.0
 * Author URI: http://themeforest.net/user/OTWthemes
 */
load_plugin_textdomain('otw_fc',false,dirname(plugin_basename(__FILE__)) . '/languages/');

$otw_fc_plugin_url = plugin_dir_url( __FILE__);

$otw_fc_js_version = '1.0';
$otw_fc_css_version = '1.0';

//include functons
require_once( plugin_dir_path( __FILE__ ).'/include/otw_fc_functions.php' );

$otw_fc_shortcode_component = false;
$otw_fc_shortcode_object = false;
$otw_fc_form_component = false;

//load core component functions
include_once( 'include/otw_components/otw_functions/otw_functions.php' );

if( !function_exists( 'otw_register_component' ) ){
	wp_die( 'Please include otw components' );
}

otw_set_up_memory_limit( '124M' );

//register shortcode component
otw_register_component( 'otw_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_shortcode/', $otw_fc_plugin_url.'/include/otw_components/otw_shortcode/' );

//register form component
otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_fc_plugin_url.'include/otw_components/otw_form/' );


/** 
 *call init plugin function
 */
add_action('init', 'otw_fc_init', 1000 );