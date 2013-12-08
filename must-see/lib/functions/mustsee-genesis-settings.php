<?php if ( !defined( 'ABSPATH' ) ) die("Sorry, you are not allowed to access this page directly.");
/**
 * Must See Genesis Settings
 *
 * This file registers the Social settings to the Theme Settings.
 *
 * Social Settings are used in post meta and social buttons.
 */


add_filter( 'genesis_theme_settings_defaults', 'mustsee_theme_settings_defaults' );
/**
 * Registers defaults for social settings
 *
 * @param array $defaults
 * @return array modified defaults
 */
function mustsee_theme_settings_defaults( $defaults ) {

	$defaults['twitter_url'] = '';
	$defaults['facebook_url'] = '';
	$defaults['pinterest_url'] = '';
	$defaults['linkedin_url'] = '';
	$defaults['youtube_url'] = '';
	$defaults['googleplus_url'] = '';
	$defaults['agent_address'] = '';
	$defaults['agent_phone'] = '';

	return $defaults;
}

add_action( 'genesis_settings_sanitizer_init', 'mustsee_register_theme_settings_sanitization_filters' );
/**
 * Sanitizes input fields
 */
function mustsee_register_theme_settings_sanitization_filters() {

	genesis_add_option_filter( 'no_html', GENESIS_SETTINGS_FIELD,
		array(
			'twitter_url',
			'agent_address',
			'agent_phone',
			'agent_email'
		) );

	genesis_add_option_filter( 'url_prepend_http', GENESIS_SETTINGS_FIELD,
		array(
			'facebook_url',
			'pinterest_url',
			'linkedin_url',
			'youtube_url',
			'googleplus_url'
		) );
}

add_filter('genesis_available_sanitizer_filters','mustsee_add_url_sanitizer', 10, 1);
/**
 * Add the url sanatizer to the default_filters
 */
function mustsee_add_url_sanitizer($default_filters) {
	$default_filters['url_prepend_http'] = 'mustsee_sanitize_url';
	return $default_filters;
}

/**
 * Prepends http:// to the url if it is not present
 */
function mustsee_sanitize_url( $new_value ) {
    if ( '' == $new_value ) { return; }

    if ( !preg_match('/http:\/\//', $new_value) ) {
        $new_value = 'http://' . $new_value;
    }

    return strip_tags( $new_value );
}

add_action('genesis_theme_settings_metaboxes', 'mustsee_register_theme_settings_metaboxes');
/**
 * Registers the Social Metabox
 *
 * @param string $_genesis_theme_settings_pagehook
 * @return void
 */
function mustsee_register_theme_settings_metaboxes( $_genesis_theme_settings_pagehook ) {
	add_meta_box('mustsee-social-settings', 'Social Links and Contact Info', 'mustsee_social_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high');
}

/**
 * Creates the Social Metabox
 */
function mustsee_social_settings_box() {
	?>

	<p>Twitter URL:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[twitter_url]" value="<?php echo esc_attr( genesis_get_option('twitter_url') ); ?>" size="50" />
	</p>

	<p>Facebook URL:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[facebook_url]" value="<?php echo esc_attr( genesis_get_option('facebook_url') ); ?>" size="50" />
	</p>

	<p>GooglePlus URL:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[googleplus_url]" value="<?php echo esc_attr( genesis_get_option('googleplus_url') ); ?>" size="50" />
	</p>

	<p>Pinterest URL:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[pinterest_url]" value="<?php echo esc_attr( genesis_get_option('pinterest_url') ); ?>" size="50" />
	</p>

	<p>LinkedIn URL:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[linkedin_url]" value="<?php echo esc_attr( genesis_get_option('linkedin_url') ); ?>" size="50" />
	</p>

	<p>YouTube Channel URL:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[youtube_url]" value="<?php echo esc_attr( genesis_get_option('youtube_url') ); ?>" size="50" />
	</p>

	<p>Address:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[agent_address]" value="<?php echo esc_attr( genesis_get_option('agent_address') ); ?>" size="50" />
	</p>

	<p>Phone:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[agent_phone]" value="<?php echo esc_attr( genesis_get_option('agent_phone') ); ?>" size="50" />
	</p>

	<p>Email:<br />
		<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[agent_email]" value="<?php echo esc_attr( genesis_get_option('agent_email') ); ?>" size="50" />
	</p>
	<?php
}

/**
 * Returns the client info according to the $info param
 *
 * @param string $info the option to return
 * @return string
 */
function mustsee_get_agent_info($info) {
	if ( 'youtube_url' == $info ) {

		return genesis_get_option('youtube_url');

	} elseif ('facebook_url' == $info ) {

		return genesis_get_option('facebook_url');

	} elseif ( 'twitter_url' == $info ) {

		return genesis_get_option('twitter_url');

	} elseif ( 'pinterest_url' == $info ) {

		return genesis_get_option('pinterest_url');

	} elseif ( 'linkedin_url' == $info ) {

		return genesis_get_option('linkedin_url');

	} elseif ( 'googleplus_url' == $info ) {

		return genesis_get_option('googleplus_url');

	} elseif ( 'address' == $info ) {

		return genesis_get_option('agent_address');

	} elseif ( 'phone' == $info ) {

		return genesis_get_option('agent_phone');

	} elseif ( 'email' == $info ) {

		return genesis_get_option('agent_email');

	} else {
		# none found
		return '';
	}
}

/**
 * Returns an array of social links
 */
function mustsee_get_social_links() {

	$social_links = array();
	$social_links['youtube'] = mustsee_get_agent_info('youtube_url');
	$social_links['facebook'] = mustsee_get_agent_info('facebook_url');
	$social_links['twitter'] = mustsee_get_agent_info('twitter_url');
	$social_links['pinterest'] = mustsee_get_agent_info('pinterest_url');
	$social_links['linkedin'] = mustsee_get_agent_info('linkedin_url');
	$social_links['google_plus'] = mustsee_get_agent_info('googleplus_url');
	$social_links['email'] = mustsee_get_agent_info('email');

	return $social_links;
}