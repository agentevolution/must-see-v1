<?php if ( !defined( 'ABSPATH' ) ) die("Sorry, you are not allowed to access this page directly.");
/**
 * Must See Footer Settings
 *
 * This file adds a Footer Page under the appearance tab.
 */

/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Must See Footer Settings Page.
 */
class Must_See_Footer_Settings extends Genesis_Admin_Boxes {

	/**
	 * Create an admin menu item and settings page.
	 */
	function __construct() {

		# Specify a unique page ID.
		$page_id = 'mustsee-footer-content';

		# Set it as a child to 'appearance', and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'themes.php',
				'page_title'  => 'Footer Content',
				'menu_title'  => 'Footer',
			)
		);

		# Set up page options. These are optional, so only uncomment if you want to change the defaults
		$page_ops = array(
		//	'screen_icon'       => 'options-general',
		//	'save_button_text'  => 'Save Settings',
		//	'reset_button_text' => 'Reset Settings',
		//	'save_notice_text'  => 'Settings saved.',
		//	'reset_notice_text' => 'Settings reset.',
		);

		# Give it a unique settings field.
		# You'll access them from genesis_get_option( 'option_name', 'mustsee-footer-settings' );
		$settings_field = 'mustsee-footer-settings';

		# Set the default values
		$default_settings = array(
			'footer-left'   => 'Copyright &copy; ' . date( 'Y' ) . ' All Rights Reserved',
			'footer-right'  => '',
			'disclaimer'    => ''
		);

		# Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		# Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );

	}

	/**
	 * Set up Sanitization Filters
	 * @since 1.0.0
	 *
	 * See /lib/classes/sanitization.php for all available filters.
	 */
	function sanitization_filters() {

		genesis_add_option_filter( 'safe_html', $this->settings_field,
			array(
				'footer-left',
				'footer-right',
				'disclaimer'
			) );
	}

	/**
	 * Set up Help Tab
	 *
	 * Genesis automatically looks for a help() function, and if provided uses it for the help tabs
	 * @link http://wpdevel.wordpress.com/2011/12/06/help-and-screen-api-changes-in-3-3/
	 */
	 function help() {
	 	$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'      => 'mustsee-footer-help',
			'title'   => 'Must See Footer',
			'content' => '<p>Use the editors below to customize the content of the footer left, footer right, and disclaimer.</p>',
		) );
	 }

	/**
	 * Register metaboxes on the Footer Settings page
	 * @since 1.0.0
	 */
	function metaboxes() {
		add_meta_box('footer_metabox', 'Footer', array( $this, 'footer_metabox' ), $this->pagehook, 'main', 'high');
	}

	/**
	 * Footer Metabox
	 * @since 1.0.0
	 */
	function footer_metabox() {

		echo '<p><strong>Footer Left:</strong></p>';
		wp_editor( $this->get_field_value( 'footer-left' ),  $this->get_field_id( 'footer-left' ), array( 'textarea_rows' => 5 ) );

		echo '<p><strong>Footer Right:</strong></p>';
		wp_editor( $this->get_field_value( 'footer-right' ), $this->get_field_id( 'footer-right' ), array( 'textarea_rows' => 5 ) );

		echo '<p><strong>Disclaimer:</strong></p>';
		wp_editor( $this->get_field_value( 'disclaimer' ),   $this->get_field_id( 'disclaimer' ), array( 'textarea_rows' => 5 ) );
	}
}

/**
 * Add the Footer page
 * @since 1.0.0
 */
function mustsee_add_footer_settings() {
	global $_mustsee_footer_settings;
	$_mustsee_footer_settings = new Must_See_Footer_Settings;
}
add_action( 'genesis_admin_menu', 'mustsee_add_footer_settings' );