<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package abertoir2022
 */

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function lineup_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'lineup/index.js';
	wp_register_script(
		'lineup-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'lineup/css/editor.min.css';
	wp_register_style(
		'lineup-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'lineup/css/style.min.css';
	wp_register_style(
		'lineup-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/lineup', [
		'api_version' => 2,
		'attributes' => [
			'content' => [
				'type' => 'string',
				'source' => 'text',
			],
		],
		'supports' => [
			'color' => ['background' => false],
			'align' => true,
			'alignWide' => true,
		],
		'title' => 'Programme line-up',
		'category' => 'abertoir',
		'editor_script' => 'lineup-block-editor',
		'editor_style'  => 'lineup-block-editor',
		'style'         => 'lineup-block',
		'render_callback' => 'render_block_lineup',
	] );
}

add_action( 'init', 'lineup_block_init' );

function render_block_lineup( $attributes, $content ) {
	$lineup = '';
	$wrapper_attributes = get_block_wrapper_attributes();

	/**
	 * @var WP_Post
	 */
	global $post;

	$now = new DateTime();
	$meta_compare = (!empty($_GET['upcoming']) ? '>=' : NULL);
	$meta_value = (!empty($_GET['upcoming']) ? $now->format('Y-m-d H:i:s') : NULL);
	$orderby = (!empty($_GET['schedule']) ? 'meta_value' : 'title');

	$args = [
		'post_type' => 'exhibit',
        'tax_query' => NULL,
        'meta_query' => NULL,
        'meta_key' => '_event_date',
        'meta_value' => $meta_value,
		'meta_compare' => $meta_compare,
        'post__in' => NULL,
        'post__not_in' => NULL,
		'post_parent__in' => [$post->ID],
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => $orderby, 
        'order' => 'ASC', 
	];

	$loop = new WP_Query( $args );

	if ($loop->have_posts()) {  
		$lineup .= '<div class="lineup-exhibit__container">';
		
		while ( $loop->have_posts() ) : $loop->the_post();
			$id = get_the_ID();
			$onesheet = '';

			if ($meta_value = get_post_meta($id, '_event_date', true)) {
				$timezone = new DateTimeZone('Europe/London');
				// sometimes wp seems to omit the below necessary for the format we are using
				// so we add it when we need it...
				if (substr_count($meta_value, ':') === 1) {
					$meta_value .= ':00';
				}

				$event_date = DateTime::createFromFormat(DATE_ATOM, $meta_value.'+00:00', $timezone);
			}

			if (empty($event_date)) {
				$event_date = new DateTime();
			}

			$tags_str = '';

			if ($tags = get_the_terms($id, 'exhibit_tags')) {
				foreach($tags as $tag) {
					$tags_str .= ' <span class="lineup-exhibit__tag">'.$tag->name.'</span>';
				}
			}

			if ($image_id = get_post_meta( $post->ID, '_listing_image_id', true )) {
				if ( $image_id && get_post( $image_id ) ) {
					$onesheet = wp_get_attachment_image( $image_id, [ 300, 300 ] );
				}
			}
		
		  	$lineup .= '<div class="lineup-exhibit">
							<div class="lineup-exhibit__picture">'.$onesheet.'</div>
							<div class="lineup-exhibit__content">
								<h3 class="lineup-exhibit__title"><a href="'.get_the_permalink().'">'.get_the_title().'</a>'.$tags_str.'</h3>
								<p class="lineup-exhibit__meta">'.$event_date?->format('D j M H:i').'</p>
								<p class="lineup-exhibit__excerpt">'.get_the_excerpt().'</p>
							</div>
						</div>';
		endwhile; 
  
		$lineup .= '</div>';
	} 
	  
	wp_reset_postdata();

	return $lineup;
}
