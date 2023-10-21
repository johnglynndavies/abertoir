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
function promote_event_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'promote-event/index.js';
	wp_register_script(
		'promote-event-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'promote-event/css/editor.min.css';
	wp_register_style(
		'promote-event-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'promote-event/css/style.min.css';
	wp_register_style(
		'promote-event-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/promote-event', [
		'api_version' => 2,
		"uses_context" => [ "postId", "postType" ],
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
		'editor_script' => 'promote-event-block-editor',
		'editor_style'  => 'promote-event-block-editor',
		'style'         => 'promote-event-block',
		'render_callback' => 'render_block_promote_event',
	] );
}

add_action( 'init', 'promote_event_block_init' );

function render_block_promote_event($attributes, $content, $block) {
	$wrapper_attributes = get_block_wrapper_attributes();

	$option = get_option( 'film-festivals_name' );
	$context_postId = !empty($block->context['postId']) && !empty($block->context['postType']) && $block->context['postType'] == 'exhibit' ? $block->context['postId'] : NULL;

	if (!empty($option['promoteselect']) || $context_postId) {
		$post_id = $context_postId ?: $option['promoteselect'];

		if ($context_postId) {
			$post = get_festival_parent($post_id);
		}
		else {
			$post = get_post($post_id);
		}
		
		if ($post->post_status !== 'publish') return NULL;

		$img = get_the_post_thumbnail($post, ['800', '450']);
		$title = get_the_title($post);
		$url = get_the_permalink($post);
		$promo = sprintf("<a href=\"%s\"><div class=\"aber-event-promo__image\">%s</div><div class=\"aber-event-promo__content\"><h3>%s</h3><p>View the programme &gt;</p></div></a>", $url, $img, $title);
	}

	if ( empty($promo) ) {
		return 'none';
	}

	return sprintf('<div %1$s>%2$s</div>', 'class="aber-event-promo"', $promo);
}

function get_festival_parent(&$post) {
	$post = get_post_parent($post);
	if (!$post) return NULL;
	$template = get_page_template_slug($post);

	if ($template !== 'wp-custom-template-festival') {
		return get_festival_parent($post);
	}

	return $post;
}