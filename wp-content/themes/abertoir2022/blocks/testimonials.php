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
function testimonials_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'testimonials/index.js';
	wp_register_script(
		'testimonials-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'testimonials/css/editor.min.css';
	wp_register_style(
		'testimonials-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'testimonials/css/style.min.css';
	wp_register_style(
		'testimonials-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/testimonials', [
		'api_version' => 2,
		"uses_context" => [ "postId", "postType" ],
		'supports' => [
			'color' => ['background' => false],
			'align' => true,
			'alignWide' => true,
		],
		'title' => 'Testimonials',
		'category' => 'abertoir',
		'editor_script' => 'testimonials-block-editor',
		'editor_style'  => 'testimonials-block-editor',
		'style'         => 'testimonials-block',
		'render_callback' => 'render_block_testimonials',
	] );
}

add_action( 'init', 'testimonials_block_init' );

/**
 * Render lineup gallery.
 */
function render_block_testimonials( $attributes, $content, $block ) {
	$slides = [];
	$wrapper_attributes = get_block_wrapper_attributes();

	$args = [
		'post_type' => 'testimonial',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => 'rand', 
	];

	$loop = new WP_Query( $args );
	

	if ($loop->have_posts()) {
		while ( $loop->have_posts() ) {
			$post = $loop->the_post();
			$title = get_the_title($post);
			$img = get_the_post_thumbnail($post, 'full');
			$content = get_the_content();
			$slides[] = sprintf("<blockquote class=\"testimonial\"><div class=\"testimonial__img\">%s</div>%s<footer>%s</footer></blockquote>", $img, $content, $title);
		}
	}

	if (!empty($slides)) {
		$slider = "<div class=\"testimonials\" data-flickity='{\"imagesLoaded\": true, \"autoPlay\": true, \"prevNextButtons\": false, \"adaptiveHeight\": true}'>".implode('', $slides).'</div>';
		return html_entity_decode($slider);
	}
}
