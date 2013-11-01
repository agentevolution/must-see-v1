<?php if ( !defined( 'ABSPATH' ) ) die("Sorry, you are not allowed to access this page directly.");

add_shortcode('social_icons','mustsee_agent_social_icons');
/**
 * Returns links with the icon class of their type wrapped in a div
 */
function mustsee_agent_social_icons() {

	$links = mustsee_get_social_links();
	$icons = '';

	foreach($links as $type => $url) {
		if ( 'email' == $type ) {
			$url = 'mailto:' . $url;
		}

		if ( '' != $url ) {
			$icons .= '<a class="icon-' . $type . '" href="' . $url . '"></a>';
		}
	}

	return '<div class="agent-social-icons clearfix">' . $icons . '</div>';
}

add_shortcode( 'agent_phone', 'agent_phone_shortcode' );
/**
 * Adds agent phone shortcode
 */
function agent_phone_shortcode() {
	$phone = genesis_get_option('agent_phone');
    return sprintf('<span class="agent-phone">%s</span>', $phone);
}

add_shortcode( 'agent_address', 'agent_address_shortcode' );
/**
 * Adds agent address shortcode
 */
function agent_address_shortcode() {
	$address = genesis_get_option('agent_address');
    return sprintf('<p class="agent-address">%s</p>', $address);
}

add_shortcode( 'button', 'mustsee_button_shortcode' );
/**
 * Adds the button shortcode
 */
function mustsee_button_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
        'size'   => 'small',
        'color'  => 'nil',
        'block'  => 0,
        'url'    => '#'
    ), $atts ) );

    $classes = 'btn btn-' . $size . ' btn-' . $color;

    if ($block) {
    	$classes .= ' btn-block';
    }

    return '<a class="' . $classes . '" href="' . $url . '">' . $content . '</a>';
}

add_shortcode( 'column', 'mustsee_column_shortcode' );
/**
 * Adds the column shortcode
 */
function mustsee_column_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'size' => '',
		'first' => 0
	), $atts ) );

	$classes = ( $first ) ? $size . ' first' : $size;

	return '<div class="' . $classes . '">' . do_shortcode($content) . '</div>';
}