<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
add_action( 'genesis_meta', 'mustsee_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 */
function mustsee_home_genesis_meta() {

	$sidebar_widget_areas = array(
		'slider',
		'welcome',
		'home-middle-left',
		'home-middle-right',
		'home-bottom'
	);

	if ( ! any_mustsee_sidebar_is_active($sidebar_widget_areas) ) {
		return;
	}

	add_filter( 'body_class', 'mustsee_custom_home_page_body_class' );

	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action( 'genesis_loop', 'mustsee_home_loop_helper' );
}

function mustsee_custom_home_page_body_class( $classes ) {
   $classes[] = 'custom-home-page';
   return $classes;
}

/**
 * Display widget content for homepage sections
 */
function mustsee_home_loop_helper() {

	if ( is_active_sidebar( 'slider' ) ) {
		echo '
		<div class="slider">
			<div class="wrap">';
			dynamic_sidebar( 'slider' );
		echo '
			</div><!-- end .wrap -->
		</div><!-- end .slider -->';
	}

	if ( is_active_sidebar( 'welcome' ) ) {
		echo '
		<div class="welcome">
			<div class="wrap">';
			dynamic_sidebar( 'welcome' );
		echo '
			</div><!-- end .wrap -->
		</div><!-- end .welcome -->';
	}

	if ( is_active_sidebar( 'home-middle-left' ) || is_active_sidebar( 'home-middle-right' ) ) {
		echo '
		<div class="home-middle clearfix">
			<div class="wrap">';
			echo '<div class="home-middle-left one-half first">';
			dynamic_sidebar( 'home-middle-left' );
			echo '</div><!-- end .home-middle-left -->';

			echo '<div class="home-middle-right one-half">';
			dynamic_sidebar( 'home-middle-right' );
			echo '</div><!-- end .home-middle-right -->';
			echo '
			</div>
		</div><!-- end .home-middle -->';
	}

	if ( is_active_sidebar( 'home-bottom' ) ) {
		echo '
		<div class="home-bottom">
			<div class="wrap">';
			dynamic_sidebar( 'home-bottom' );
		echo '
			</div><!-- end .wrap -->
		</div><!-- end .home-bottom -->';
	}
}

/**
 * Returns true if any of the mustsee sidebars are active
 *
 * @param array $sidebar_widget_areas
 * @return bool
 */
function any_mustsee_sidebar_is_active($sidebar_widget_areas) {
	foreach($sidebar_widget_areas as $sidebar) {
		if ( is_active_sidebar($sidebar) ) {
			return true;
		}
	}

	return false;
}

genesis();