<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
add_action( 'genesis_meta', 'mustsee_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 */
function mustsee_home_genesis_meta() {

	$sidebar_widget_areas = array(
		'home-top',
		'home-middle',
		'home-bottom-left',
		'home-bottom-right'
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

	if ( is_active_sidebar( 'home-top' ) ) {
		echo '
		<div class="home-top">
			<div class="wrap">';
			dynamic_sidebar( 'home-top' );
		echo '
			</div><!-- end .wrap -->
		</div><!-- end .home-top -->';
	}

	if ( is_active_sidebar( 'home-middle' ) ) {
		echo '
		<div class="home-middle">
			<div class="wrap">';
			dynamic_sidebar( 'home-middle' );
		echo '
			</div><!-- end .wrap -->
		</div><!-- end .home-middle -->';
	}

	if ( is_active_sidebar( 'home-bottom-left' ) || is_active_sidebar( 'home-bottom-right' ) ) {
		echo '
		<div class="home-bottom clearfix">
			<div class="wrap">';
			echo '<div class="home-bottom-left two-thirds first">';
			dynamic_sidebar( 'home-bottom-left' );
			echo '</div><!-- end .home-bottom-left -->';

			echo '<div class="home-bottom-right one-third">';
			dynamic_sidebar( 'home-bottom-right' );
			echo '</div><!-- end .home-bottom-right -->';
			echo '
			</div>
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