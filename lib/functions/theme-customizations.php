<?php if ( !defined( 'ABSPATH' ) ) die("Sorry, you are not allowed to access this page directly.");

add_action( 'customize_register'    , array( 'Must_See_Theme_Options', 'register'            ) );
add_action( 'init'                  , array( 'Must_See_Theme_Options', 'remove_all_mods'     ) );
add_action( 'init'                  , array( 'Must_See_Theme_Options', 'remove_section_mods' ) );
add_action( 'wp_head'               , array( 'Must_See_Theme_Options', 'render'              ) );
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 */
class Must_See_Theme_Options {

	/**
	 * Registers the settings with WordPress.
	 *
	 * Used by hook: 'customize_register'
	 *
	 * @see add_action('customize_register',$func)
	 * @param WP_Customize_Manager $wp_customize
	 */
	public static function register( $wp_customize ) {

		# Sections
		# ================================================================

		$wp_customize->add_section('mustsee_header', array(
				'title'     => __( 'Header Styling', 'mustsee' ),
				'priority'  => 35
		) );

		// home featured image, home properties section
		$wp_customize->add_section('mustsee_homepage', array(
				'title'     => __( 'Home Page Styling', 'mustsee' ),
				'priority'  => 40
		) );

				$wp_customize->add_section('mustsee_options', array(
				'title'     => __( 'Options', 'mustsee' ),
				'priority'  => 45
		) );


		# Settings & Controls
		# ================================================================


		/* Site Title and Tagline
		 --------------------------------------------------*/
		// Header logo or text
		$wp_customize->add_setting('logo_display_type', array(
			'default'   => 'text'
		) );

				$wp_customize->add_setting('logo_image', array(
			'default' => get_stylesheet_directory_uri() . '/images/logo-default.png'
		) );

		// Logo Image width (for display at small screen sizes)
		$wp_customize->add_setting('logo_image_width', array(
			'default' => '362'
		) );

		// Logo display type
		$wp_customize->add_control( 'logo_display_type', array(
			'label'      => __( 'Logo Display Type', 'mustsee' ),
			'section'    => 'title_tagline',
			'settings'   => 'logo_display_type',
			'priority'   => 1,
			'type'       => 'radio',
			'choices'    => array(
				'image'  => 'Custom Image',
				'text'   => 'Use site title and tagline as logo'
			)
		) );

		// Logo Image
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_image', array(
			'label'      => __( 'Custom Logo - Must be 480x70 or smaller', 'mustsee' ),
			'section'    => 'title_tagline',
			'settings'   => 'logo_image',
			'priority'   => 4
		) ) );

		// Logo Image Width
		$wp_customize->add_control('logo_image_width', array(
			'label'    => __('Logo Image Width in pixels (necessary for centering on small screens)', 'mustsee' ),
			'section'  => 'title_tagline',
			'settings' => 'logo_image_width',
			'priority' => 5
		) );


		/* Homepage
		 --------------------------------------------------*/

		$wp_customize->add_setting('home_featured_image', array(
			'default' => get_stylesheet_directory_uri() . '/images/home-featured-default.jpg'
		) );

		// Home Featured Image
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'home_featured_image', array(
				'label'      => __( 'Home Featured Image - Use a 1200x301 pixel image for best results', 'mustsee' ),
				'section'    => 'mustsee_homepage',
				'settings'   => 'home_featured_image',
				'priority'   => 1
		) ) );


		/* Options
		 --------------------------------------------------*/

		$wp_customize->add_setting('remove_all_mods');
		$wp_customize->add_setting('remove_section_mods');

		// Remove All Mods
		$wp_customize->add_control( 'remove_all_mods', array(
			'label'      => __( 'Remove All Customizations', 'mustsee' ),
			'section'    => 'mustsee_options',
			'settings'   => 'remove_all_mods',
			'type'       => 'checkbox'
		) );

		// Remove mods by section
		$wp_customize->add_control( 'remove_section_mods', array(
			'label'      => __( 'Remove Customizations By Section. Select one, press save, and refresh the page to see the changes.', 'mustsee' ),
			'section'    => 'mustsee_options',
			'settings'   => 'remove_section_mods',
			'type'       => 'radio',
			'choices'    => array(
				'header'          => 'Header Styling',
				'homepage'        => 'Home Page',
				'footer_widgets'  => 'Footer Widgets'
			)
		) );
	}

	/**
	 * This will output the custom WordPress settings to the theme's WP head.
	 *
	 * Used by hook: 'wp_head'
	 *
	 * @see add_action('wp_head',$func)
	 */
	public static function render() {
		?>
		<!-- begin Customizer CSS -->
		<style type="text/css">
			<?php
			// Custom logo
			self::generate_css('.header-image .title-area a', 'background-image', 'logo_image', 'url(', ')' );

			// Logo Image Width
			self::generate_css('.header-image .site-title a', 'width', 'logo_image_width', '', 'px');

			// Home Featured Image
			self::generate_css('.home .home-top', 'background-image', 'home_featured_image', 'url(', ')' );

			?>
		</style>
		<!-- end Customizer CSS -->
		<?php
	}

	/**
	 * Generates a line of CSS for use in header output.
	 *
	 * If the setting ($mod_name) has no defined value, the CSS will not be output.
	 *
	 * @uses get_theme_mod()
	 * @param string $selector CSS selector
	 * @param string $style The name of the CSS property to modify
	 * @param string $mod_name The name of the theme_mod option to fetch
	 * @param string $prefix Optional. Anything that needs to be output before the CSS property
	 * @param string $postfix Optional. Anything that needs to be output after the CSS property
	 * @param bool $echo Optional. Whether to print directly to the page (default: true).
	 * @return string Returns a single line of CSS with selectors and a property.
	 * @since Nouveau 1.0
	 */
	public static function generate_css($selector,$style,$mod_name,$prefix='',$postfix='',$echo=true) {

		$mod = get_theme_mod($mod_name);

		if ( empty($mod) ) {
			return '';
		}

		$output = sprintf('%s { %s:%s; }',
			$selector,
			$style,
			$prefix . $mod . $postfix
		);

		$output .= "\n";

		if ( $echo ) {
			echo $output;
		}

		return $output;
	}

	public static function hide_elements($selector, $mod) {

		$mod = get_theme_mod($mod);

		if ( empty($mod) ) {
			return;
		}

		echo $selector, '{
			display: none;
		}';
	}

	/**
	 * Output css conditionally
	 *
	 * @param string $css the css to output
	 * @param string $mod the mod to check against
	 * @param bool $active if true outputs the css when the mod is active
	 */
	public static function css($css, $mod, $active = 1) {

		$mod = get_theme_mod($mod);

		if ( empty($mod) && $active == 1 ) {
			return;
		}

		echo $css;
	}

	public static function get_mods() {
		return array(
			'logo_display_type',
			'logo_image',
			'logo_image_width',
			
			'home_featured_image'
		);
	}

	public static function remove_all_mods() {

		if ( 1 != get_theme_mod('remove_all_mods') ) {
			return;
		}

		$mods = self::get_mods();

		foreach($mods as $mod) {
			remove_theme_mod($mod);
		}

		remove_theme_mod('remove_all_mods');
	}

	/**
	 * Returns an array of mods for the specified section
	 *
	 * Available sections:
	 * header
	 * homepage
	 *
	 * @param string $section the section to return
	 */
	public static function get_section_mods( $section ) {

		switch ( $section ) {

			case 'title_tagline':
				$output = array(
					'logo_display_type',
					'logo_image',
					'logo_image_width'
				);
				break;

			case 'homepage':
				$output = array(
					'home_featured_image'
				);

			default:
				$output = false;

		}

		return $output;
	}

	public static function remove_section_mods() {

		$section = get_theme_mod('remove_section_mods');

		if ( !$section ) {
			return;
		}

		$mods = self::get_section_mods($section);

		if ( false == $mods ) {
			return;
		}

		foreach($mods as $mod) {
			remove_theme_mod($mod);
		}

		remove_theme_mod('remove_section_mods');
	}
}