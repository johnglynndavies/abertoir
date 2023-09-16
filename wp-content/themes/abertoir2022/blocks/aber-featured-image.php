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
 */
function aber_featured_image_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'aber-featured-image/index.js';
	wp_register_script(
		'aber-featured-image-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	register_block_type( 'abertoir2022/featured-image', [
		'api_version' => 2,
		'attributes' => [
			'className' => [
				'type' => 'string',
			],
		],
		'supports' => [
			'color' => ['background' => false, 'link' => false],
			'align' => true,
		],
		'editor_script' => 'aber-featured-image-block-editor',
	] );
}

add_action( 'init', 'aber_featured_image_block_init' );
