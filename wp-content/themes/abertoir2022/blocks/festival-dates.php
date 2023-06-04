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
function festival_dates_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'festival-dates/index.js';
	wp_register_script(
		'festival-dates-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'festival-dates/editor.css';
	wp_register_style(
		'festival-dates-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'festival-dates/style.css';
	wp_register_style(
		'festival-dates-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/festival-dates', [
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
		'editor_script' => 'festival-dates-block-editor',
		'editor_style'  => 'festival-dates-block-editor',
		'style'         => 'festival-dates-block',
		'render_callback' => 'render_block_festival_dates',
	] );
}

add_action( 'init', 'festival_dates_block_init' );

function render_block_festival_dates( $attributes, $content ) {
	$wrapper_attributes = get_block_wrapper_attributes();
	// get date range from latest festival run
	$festival_dates = '19 – 24 November 2023';

	if ( ! $festival_dates ) {
		return;
	}

	return sprintf('<p %1$s>%2$s</p>', 'class="aber-header__dates"', $festival_dates);
}
