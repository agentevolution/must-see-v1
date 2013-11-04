<?php if ( !defined( 'ABSPATH' ) ) die("Sorry, you are not allowed to access this page directly.");
/**
 * Functions
 */

/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 */
add_action('genesis_setup','mustsee_theme_setup', 15);
function mustsee_theme_setup() {

	# Backend
	# ========================================================================

	# Image Sizes
	add_image_size('mini', 75, 75, true);
	add_image_size('homepage-posts', 535, 170, true);

	# Sidebars
	unregister_sidebar('sidebar-alt');
	add_theme_support('genesis-footer-widgets', 4);

	# Remove Unused Page Layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );

	# Remove Unused User Settings
	add_filter('user_contactmethods', 'mustsee_contactmethods');
	remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

	# Include footer settings
	require_once get_stylesheet_directory() . '/lib/functions/mustsee-footer-settings.php';

	# Customize link under the appearance tab
	add_action('admin_menu', 'mustsee_customize_menu_link');

	# add documentation link to menu bar
	add_action('wp_before_admin_bar_render', 'mustsee_add_documentation_link_to_admin_bar');

	# disable nav menus
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	remove_action( 'genesis_after_header', 'genesis_do_subnav' );

	# add excerpts to pages
	add_action( 'init', 'mustsee_add_excerpts_to_pages' );

	mustsee_register_sidebars();


	# Frontend
	# ========================================================================

	# HTML5 Doctype
	add_theme_support( 'html5' );

	# Favicon
	add_filter( 'genesis_pre_load_favicon', 'mustsee_favicon_filter' );

	# Responsive Meta Tag
	add_action('genesis_meta', 'mustsee_viewport_meta_tag');

	# Before Header
	add_action('genesis_before_header', 'mustsee_before_header');

	# Footer
	remove_action('genesis_footer', 'genesis_do_footer');
	add_action('genesis_footer', 'mustsee_footer');

	# Add google web fonts link to head
	add_action('genesis_meta','mustsee_web_fonts');

	# Structural Wraps
	add_theme_support('genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ));

	# Enable shortcode in widgets
	add_filter('widget_text','do_shortcode');

	# Load theme scripts
	add_action('wp_enqueue_scripts', 'mustsee_load_scripts');

	# Header image body class
	remove_filter('body_class', 'genesis_header_body_classes');
	add_filter('body_class', 'mustsee_custom_logo_body_class');

	# IE8 and down CSS
	add_action('wp_head', 'mustsee_load_ie8_css');
}

# Backend Functions
# ========================================================================

/**
 * Customize Contact Methods
 * @link http://sillybean.net/2010/01/creating-a-user-directory-part-1-changing-user-contact-fields/
 *
 * @param array $contactmethods
 * @return array
 */
function mustsee_contactmethods( $contactmethods ) {
	unset( $contactmethods['aim'] );
	unset( $contactmethods['yim'] );
	unset( $contactmethods['jabber'] );

	return $contactmethods;
}

/**
 * Don't Update Theme
 *
 * If there is a theme in the repo with the same name,
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r request arguments
 * @param string $url request url
 * @return array request arguments
 */
function mustsee_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

function mustsee_register_sidebars() {
	genesis_register_sidebar( array(
		'id'			=> 'top-header',
		'name'			=> 'Top Header',
		'description'	=> 'This is the small bar above the header'
	) );
	genesis_register_sidebar( array(
		'id'			=> 'slider',
		'name'			=> 'Slider',
		'description'	=> 'This is the Slider section'
	) );
	genesis_register_sidebar( array(
		'id'			=> 'welcome',
		'name'			=> 'Welcome',
		'description'	=> 'This is the Welcome section'
	) );
	genesis_register_sidebar( array(
		'id'			=> 'home-middle-left',
		'name'			=> 'Home Middle Left',
		'description'	=> 'This is the Home Middle Left section'
	) );
	genesis_register_sidebar( array(
		'id'			=> 'home-middle-right',
		'name'			=> 'Home Middle Right',
		'description'	=> 'This is the Home Middle Right section'
	) );
	genesis_register_sidebar( array(
		'id'			=> 'home-bottom',
		'name'			=> 'Home Bottom',
		'description'	=> 'This is the Home Bottom section'
	) );
}

function mustsee_add_documentation_link_to_admin_bar() {

	global $wp_admin_bar;

	$wp_admin_bar->add_menu( array(
		'parent' => false,
		'id' => 'theme-setup-guide',
		'title' => 'Theme Setup Guide',
		'href' => 'http://themes.agentevolution.com/guides/must-see-theme-setup-guide',
		'meta' => array('target' => '_blank')
	));
}

/**
 * Adds the customize link to the admin menu under the appearance tab
 */
function mustsee_customize_menu_link() {
    add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' );
}

/**
 * Adds support for excerpts on pages
 */
function mustsee_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}


# Frontend Functions
# ========================================================================

/**
 * Loads custom favicon
 */
function mustsee_favicon_filter() {
	return get_stylesheet_directory_uri() . '/images/favicon.ico';
}

/**
 * Viewport Meta Tag for Mobile Browsers
 */
function mustsee_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/**
 * Add widget area before header
 */
function mustsee_before_header() {
	if ( is_active_sidebar( 'top-header' ) ) {
		echo '
		<div class="top-header">
			<div class="wrap">';
			dynamic_sidebar( 'top-header' );
		echo '
			</div><!-- end .wrap -->
		</div><!-- end .top-header -->';
	}
}

/**
 * Footer
 */
function mustsee_footer() {
	echo '
	<div class="one-half first" id="footer-left">',
		do_shortcode(wpautop( genesis_get_option( 'footer-left', 'mustsee-footer-settings' ) )),
	'</div>
	<div class="one-half" id="footer-right">',
		agentevo_footer_copy(),
		do_shortcode(wpautop( genesis_get_option( 'footer-right', 'mustsee-footer-settings' ) )),
	'</div>
	<div id="footer-disclaimer">',
		do_shortcode(wpautop( genesis_get_option( 'disclaimer', 'mustsee-footer-settings' ) )),
	'</div>';
}

/**
 * Returns a random line of text
 *
 * @return string
 */
function agentevo_footer_copy() {

    $footer_copy = array(
        'WordPress Real Estate Themes',
        'WordPress Themes for Real Estate Agents',
        'Real Estate WordPress Themes',
        'WordPress Themes with IDX',
        'Real Estate Themes for WordPress',
    );

    $key = array_rand($footer_copy);

    $output = '<p class="credits">';

    if ( is_home() || is_front_page() ) {
        $output .= '<a href="http://themes.agentevolution.com">' . $footer_copy[$key] . '</a>';
    } else {
        $output .= '<a href="http://themes.agentevolution.com/shop/must-see">Must See Theme</a>';
    }

    $output .= '</p>';

    return $output;
}

/**
 * Adds link to document head for google web fonts
 */
function mustsee_web_fonts() {
	echo "<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,400italic|Crimson+Text' rel='stylesheet' type='text/css'>";
}

/**
 * Loads the required theme scripts
 *
 * Registers jquery masonry but does not enqueue it
 */
function mustsee_load_scripts() {
	wp_enqueue_script('jquery');
	wp_register_script('masonry', CHILD_URL . '/lib/js/jquery.masonry.min.js', array('jquery'), false, true);
}

/**
 * Adds header-image to body classes if the site title is an image
 */
function mustsee_custom_logo_body_class( $classes ) {

	if ( 'image' == get_theme_mod('logo_display_type') ) {
		$classes[] = 'header-image';
	}

	return $classes;
}

/**
 * Loads a small bit of CSS into the document head for IE8 and down
 */
function mustsee_load_ie8_css() {
	?>
	<!--[if lt IE 9]>
		<style>
		.wrap {
			width: 1140px;
			margin: 0 auto;
		}
		</style>
	<![endif]-->
	<?php
}

# Theme Updater
# ===========================================================
/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'mustsee_child_theme_setup', 11 );

/**
 * Theme setup function.
 */
function mustsee_child_theme_setup(){

    /* updater args */
    $updater_args = array(
        'repo_uri'  => 'http://themes.agentevolution.com/',
        'repo_slug' => 'must-see',
    );

    /* add support for updater */
    add_theme_support( 'auto-hosted-child-theme-updater', $updater_args );
}

/* Load Child Theme Updater */
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/functions/child-theme-updater.php' );
new Must_See_Child_Theme_Updater;

# Includes
# ===========================================================

# Shortcodes
require_once get_stylesheet_directory() . '/lib/functions/shortcodes.php';

# Include mustsee additions to genesis settings
require_once get_stylesheet_directory() . '/lib/functions/mustsee-genesis-settings.php';

# Theme Customizatons
require_once get_stylesheet_directory() . '/lib/functions/theme-customizations.php';

# Custom CSS editor
require_once get_stylesheet_directory() . '/lib/functions/custom-css.php';