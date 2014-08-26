<?php
/*
Plugin Name: Plugin Rater
Plugin URI: http://natasha.jp/
Description: It makes you access to plugins pages of wordpress.org easier.
Author: natashanatashanatashanatasha
Contributors: natashanatashanatashanatasha, hissy
Version: 0.1
Author URI: http://natasha.jp/
License: GPL2+
Text Domain: plugin-rater
Domain Path: /languages/
*/


function plugin_rate_add_plugins_columns( $columns ) {
	$columns['plugin_rate'] = 'Rate';
	return $columns;
}
add_filter( 'manage_plugins_columns', 'plugin_rate_add_plugins_columns' );
 
function plugin_rate_manage_plugins_column( $column_name, $plugin_file, $plugin_data ) {
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	if ( 'plugin_rate' == $column_name ) {
		$slug = split('/', $plugin_file);
		$info = plugins_api( 'plugin_information', array('slug'=>$slug[0]) );
		$plugin_name = sanitize_title( $plugin_data['TextDomain'] );
		$plugin_orgurl = 'https://wordpress.org/plugins/'.$plugin_name.'/';
		$lets_rate = '<a href="' .$plugin_orgurl .'" target="_blank">Let\'s Rate!!</a>';
		if (!is_wp_error($info)) {
			wp_star_rating( array( 'rating' => $info->rating, 'type' => 'percent', 'number' => $info->num_ratings ) );
			echo($lets_rate);
		}
	}
}
add_filter( 'manage_plugins_custom_column', 'plugin_rate_manage_plugins_column', 10, 3 );