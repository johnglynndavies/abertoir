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
function lang_switcher_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'lang-switcher/index.js';
	wp_register_script(
		'lang-switcher-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'lang-switcher/css/editor.min.css';
	wp_register_style(
		'lang-switcher-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'lang-switcher/css/style.min.css';
	wp_register_style(
		'lang-switcher-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/lang-switcher', [
		'api_version' => 2,
		'attributes' => [
		'content' => [
			'type' => 'string',
			'source' => 'text',
		],
		],
		'supports' => [
			'color' => ['background' => false, 'link' => true],
			'align' => true,
		],
		'title' => 'Language Switcher',
		'category' => 'aber-blocks',
		'editor_script' => 'lang-switcher-block-editor',
		'editor_style'  => 'lang-switcher-block-editor',
		'style'         => 'lang-switcher-block',
		'render_callback' => 'render_block_lang_switcher',
	] );
}

add_action( 'init', 'lang_switcher_block_init' );

function render_block_lang_switcher( $attributes, $content ) {
	$wrapper_attributes = get_block_wrapper_attributes();
	//var_dump($wrapper_attributes);die;

	if ( ! function_exists( 'pll_the_languages' ) ) {
		return;
	}

	$output = [];
	$langs = pll_the_languages(['raw'=> 1]);

	foreach ($langs as $key => $lang) {
		$output[] = sprintf('<a class="%1$s" href="%2$s"><abbr class="d-none-md" title="%3$s">%4$s</abbr><span class="d-none d-inline-md">%3$s</span></a>', implode(' ', $lang['classes']), $lang['url'], $lang['name'], $key);
	}

	return sprintf('<div %1$s>%2$s</div>', 'class="aber-langswitcher"', implode(' &nbsp;|&nbsp;  ', $output));
}
