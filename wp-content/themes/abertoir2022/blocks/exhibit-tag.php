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
function exhibit_tag_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'exhibit-tag/index.js';
	wp_register_script(
		'exhibit-tag-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-api-fetch',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'exhibit-tag/css/editor.min.css';
	wp_register_style(
		'exhibit-tag-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'exhibit-tag/css/style.min.css';
	wp_register_style(
		'exhibit-tag-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/exhibit-tag', [
		'api_version' => 2,
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
		'editor_script' => 'exhibit-tag-block-editor',
		'editor_style'  => 'exhibit-tag-block-editor',
		'style'         => 'exhibit-tag-block',
		'render_callback' => 'render_block_exhibit_tag',
	] );
}

add_action( 'init', 'exhibit_tag_block_init' );

function render_block_exhibit_tag( $attributes, $content ) {
	$wrapper_attributes = get_block_wrapper_attributes();
	$output = [];

	$post_id = get_the_ID();

	// get tags from current exhibit
	if (!$tags = get_the_terms($post_id, 'exhibit_tags')) {
		return;
	}

	foreach($tags as $tag) {
		$output[] = sprintf('<li %1$s>%2$s</li>', 'class="exhibit-tags__tag"', $tag->name);
	}

	return '<ul class="exhibit-tags">'.implode(' ', $output).'</ul>';
}
