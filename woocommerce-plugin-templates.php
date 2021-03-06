<?php
/**
 * Plugin Name: 	WooCommerce Plugin Templates
 * Plugin URI:		http://jeroensormani.com
 * Description:		A simple demo plugin on how to use template files within your plugin.
 */


/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/woocommerce-plugin-templates/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/woocommerce-plugin-templates/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 							Path to the template file.
 */
function wcpt_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	// Set variable to search in woocommerce-plugin-templates folder of theme.
	if ( ! $template_path ) :
		$template_path = 'woocommerce-plugin-templates/';
	endif;

	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = plugin_dir_path( __FILE__ ) . 'templates/'; // Path to the template folder
	endif;

	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );

	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;

	return apply_filters( 'wcpt_locate_template', $template, $template_name, $template_path, $default_path );

}


/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see wcpt_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args					Args passed for the template file.
 * @param string 	$string $template_path	Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function wcpt_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {

	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;

	$template_file = wcpt_locate_template( $template_name, $tempate_path, $default_path );

	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;

	include $template_file;

}


/**
 * Redeem Gift Card.
 *
 * The redeem gift card shortcode will output the template
 * file from the templates/folder.
 *
 * @since 1.0.0
 */
function wcpt_gift_card_shortcode() {

	return wcpt_get_template( 'redeem-gift-card.php' );

}
add_shortcode( 'redeem_gift_card', 'wcpt_gift_card_shortcode' );


/**
 * Template loader.
 *
 * The template loader will check if WP is loading a template
 * for a specific Post Type and will try to load the template
 * from out 'templates' directory.
 *
 * @since 1.0.0
 *
 * @param	string	$template	Template file that is being loaded.
 * @return	string				Template file that should be loaded.
 */
function wcpt_template_loader( $template ) {

	$find = array();
	$file = '';

	if ( is_singular( 'post' ) ) :
		$file = 'post-override.php';
	elseif ( is_singular( 'page' ) ) :
		$file = 'page-override.php';
	endif;

	if ( file_exists( wcpt_locate_template( $file ) ) ) :
		$template = wcpt_locate_template( $file );
	endif;

	return $template;

}
// add_filter( 'template_include', 'wcpt_template_loader' );
// Commented out as this can screw things up a bit, uncomment if you want to override template files.

