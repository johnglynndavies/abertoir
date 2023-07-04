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
function exhibit_meta_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'exhibit-meta/index.js';
	wp_register_script(
		'exhibit-meta-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-block-editor',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'exhibit-meta/css/editor.min.css';
	wp_register_style(
		'exhibit-meta-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'exhibit-meta/css/style.min.css';
	wp_register_style(
		'exhibit-meta-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/exhibit-meta', [
		'api_version' => 3,
		'attributes' => [
			'className' => [
				'type' => 'string',
			],
		'content' => [
			'type' => 'string',
			'source' => 'text',
		],
		],
		'supports' => [
			'color' => ['background' => false, 'link' => false],
			'align' => false,
		],
		'editor_script' => 'exhibit-meta-block-editor',
		'editor_style'  => 'exhibit-meta-block-editor',
		'style'         => 'exhibit-meta-block',
	] );
}

add_action( 'init', 'exhibit_meta_block_init' );
