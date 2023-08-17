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
function lineup_gallery_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'lineup-gallery/index.js';
	wp_register_script(
		'lineup-gallery-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'lineup-gallery/css/editor.min.css';
	wp_register_style(
		'lineup-gallery-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'lineup-gallery/css/style.min.css';
	wp_register_style(
		'lineup-gallery-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/lineup-gallery', [
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
		'title' => 'Line-up gallery',
		'category' => 'abertoir',
		'editor_script' => 'lineup-gallery-block-editor',
		'editor_style'  => 'lineup-gallery-block-editor',
		'style'         => 'lineup-gallery-block',
		'render_callback' => 'render_block_lineup_gallery',
	] );
}

add_action( 'init', 'lineup_gallery_block_init' );

function render_block_lineup_gallery( $attributes, $content ) {
	
	$wrapper_attributes = get_block_wrapper_attributes();

	$option = get_option( 'film-festivals_name' );
	if (empty($option['sliderselect'])) {
		return;
	}

	$children = get_children([
		'post_parent' => $option['sliderselect'],
		'post_type' => 'exhibit',
		'post_status' => 'publish',
	]);

	foreach($children as $id => $child) {
		if (get_page_template_slug($child) === "wp-custom-template-line-up") {
			$lineup = get_children([
				'post_parent' => $id,
				'post_type' => 'exhibit',
				'post_status' => 'publish',
			]);
		}
	}

	$pictures = [];

	foreach($lineup as $id => $exhibit) {
		if ($panoramic = lineup_gallery_Image($id, 'panoramic')) {
			if ($square = lineup_gallery_Image($id, 'square_2x')) {
				$pictures[] = '<figure><picture class="carousel-cell">
				<source srcset="'.$square[0].'" media="(max-width: 768px)">
				<img alt="" src="'.$panoramic[0].'" />
				</picture><figcaption>The Thing</figcaption></figure>';
			}
		}
	}

	$gallery = "<div class=\"lineup-gallery alignfull\" data-flickity='{\"imagesLoaded\": true, \"autoPlay\": true}'>".implode('', $pictures).'</div>';

return html_entity_decode($gallery);

	
	
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
		$gallery .= '<div class="lineup-exhibit__container">';
		
		while ( $loop->have_posts() ) : $loop->the_post();
			$id = get_the_ID();

			

			//get_the_permalink()
			//get_the_title()


		
		  	$gallery .= '<img src="" />';
		endwhile; 
  
		$gallery .= '';
	} 
	  
	wp_reset_postdata();

	return $gallery;
}


function lineup_gallery_Image(int $post_id, string $size) {
	$image = false;

	if (has_post_thumbnail($post_id)) {
		//$thumb = get_the_post_thumbnail( $post_id, 'thumbnail',$attributes );
		$post_thumbnail_id 	= get_post_thumbnail_id($post_id);
		$image = wp_get_attachment_image_src($post_thumbnail_id, $size);
	}

	return $image;
}