<?php
/**
 * Template Name: Communities
 */

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'community_template_content' );
remove_action( 'genesis_entry_header','genesis_do_post_title' );
/**
 * Outputs markup for the communities template
 */
function community_template_content() {

	the_content();

	global $post;
	$args = array(
		'post_parent' => $post->ID,
		'post_type' => 'page',
		'order'    => 'ASC',
		'posts_per_page'=>-1
	);

	$cq = new WP_Query($args);

	while ($cq->have_posts()) : $cq->the_post();
?>
	<div class="masonry-item">
		<h2>
			<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<a href="<?php the_permalink(); ?>">
			<?php echo genesis_get_image('medium'); ?>
		</a>
		<?php printf('<div class="neighborhood-excerpt">%s</div>', get_the_content_limit( 300, '[Read More]') ); ?>
	</div><!-- .masonry-item -->
	<?php
	endwhile;
	wp_reset_postdata();
}

add_action('wp_enqueue_scripts', 'community_template_scripts');
function community_template_scripts() {
	wp_enqueue_script('masonry');
}

add_action('wp_footer', 'community_template_masonry_js', 999);
/**
 * Initializes jquery masonry
 */
function community_template_masonry_js() {
	?>
	<script>
	jQuery(document).ready(function($) {
		var $container = $('.entry-content');
		$container.imagesLoaded(function(){
			$container.masonry({
				itemSelector : '.masonry-item',
				columnWidth : 320,
				isAnimated : true
			});
		});
	});
	</script>
	<?php
}

genesis();