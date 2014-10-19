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
Domain Path: /languages
*/

define( 'PLUGIN_RATER_NONCE', date('Ymd') );
define( 'PLUGIN_RATER_EXPIRES', 86400 ); // 1day


function plugin_rater_add_plugins_columns( $columns ) {
	$columns['plugin_rate'] = 'Rate';
	return $columns;
}

add_filter( 'manage_plugins_columns', 'plugin_rater_add_plugins_columns' );

function plugin_rater_manage_plugins_column( $column_name, $plugin_file, $plugin_data ) {
	if ( isset( $plugin_data['slug'] ) && $plugin_data['slug'] ) {
		printf(
			'<div class="plugin-rate" data-slug="%s" style="white-space: nowrap;"></div>',
			esc_attr( $plugin_data['slug'] )
		);
		printf(
			'<div class="plugin-rate-url" style="white-space: nowrap;"><a href="%s">%s</a></div>',
			esc_url( 'https://wordpress.org/support/view/plugin-reviews/' . $plugin_data['slug'] ),
			esc_html( __( "Let's Rate!!", "plugin-rater" ) )
		);
	}
}

add_filter( 'manage_plugins_custom_column', 'plugin_rater_manage_plugins_column', 10, 3 );


/*
 * Internationalization
 * @param  none
 * @return none
 * @since  0.1
 */
function plugin_rater_plugins_loaded() {
    load_plugin_textdomain(
        "plugin-rater",
        false,
        dirname(plugin_basename(__FILE__)).'/languages'
    );
}

add_action( 'plugins_loaded', 'plugin_rater_plugins_loaded' );


/*
 * Get plugin info from wordpress.org API and out put as html
 * @param  none
 * @return none
 * @since  0.1
 */
function wp_ajax_plugin_rater() {
	check_ajax_referer( PLUGIN_RATER_NONCE, 'nonce' );

	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	$info = plugins_api( 'plugin_information', array( 'slug' => $_GET['slug'] ) );

	if (!is_wp_error($info)) {
		header( 'Content-Type: text/html; charset=UTF-8' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s T', time() ) );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s T', time() + PLUGIN_RATER_EXPIRES ) );
		header( 'Cache-Control: public, max-age=' . PLUGIN_RATER_EXPIRES );
		header('Pragma: cache');
		wp_star_rating( array(
			'rating' => $info->rating,
			'type' => 'percent',
			'number' => $info->num_ratings
		) );
	}
	exit;
}

add_action( 'wp_ajax_plugin_rater', 'wp_ajax_plugin_rater' );


/*
 * Output JavaScript at wp-admin/plugins.php
 * @param  none
 * @return none
 * @since  0.1
 */
function plugin_rater_admin_footer() {
?>
<script type="text/javascript">
(function($){
	$('.plugin-rate').each(function(){
		var self = this;
		var args = {
			'slug':   $(this).data('slug'),
			'nonce':  '<?php echo wp_create_nonce( PLUGIN_RATER_NONCE ); ?>',
			'action': 'plugin_rater'
		};
		$.get('/wp-admin/admin-ajax.php', args, function(data){
			$(self).html(data);
		}, 'html');
	});
})(jQuery);
</script>
<?php
}

add_action( 'admin_footer-plugins.php', 'plugin_rater_admin_footer' );
