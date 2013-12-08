<?php
/**
 * Template Name: Full width
 * This template forces a full width layout. Used for dsIDXpress and other IDXs
 * where a virtual page is used and a page template in the theme can be selected.
 */

add_filter('genesis_pre_get_option_site_layout', 'idx_full_layout');
function idx_full_layout($opt) {
    $opt = 'full-width-content';
	return $opt;
}

genesis();