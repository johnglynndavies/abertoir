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
function event_time_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'event-time/index.js';
	wp_register_script(
		'event-time-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-api-fetch',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'event-time/css/editor.min.css';
	wp_register_style(
		'event-time-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'event-time/css/style.min.css';
	wp_register_style(
		'event-time-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/event-time', [
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
		'editor_script' => 'event-time-block-editor',
		'editor_style'  => 'event-time-block-editor',
		'style'         => 'event-time-block',
		'render_callback' => 'render_block_event_time',
	] );
}

add_action( 'init', 'event_time_block_init' );

function render_block_event_time( $attributes, $content ) {
	$wrapper_attributes = get_block_wrapper_attributes();
	// get event time from current exhibit
	$post_id = get_the_ID();
	$meta_value = get_post_meta($post_id, '_event_date', true);

	if (!$meta_value) return;

	$timezone = new DateTimeZone('Europe/London');
	// sometimes wp seems to omit the below necessary for the format we are using
	// so we add it when we need it...
	if (substr_count($meta_value, ':') === 1) {
		$meta_value .= ':00';
	}

	if (!$start_time = DateTime::createFromFormat(DATE_ATOM, $meta_value.'+00:00', $timezone)) {
		return '';
	}

	$event_str = $start_time->format('D j M H:i');

	/*
	// handle multiple events
	$events = [];
	if (is_serialized($vals)) {
    $vals = unserialize($vals)
  	}
	foreach($vals as $val) {
		if (!($start_time = DateTime::createFromFormat(DATE_ATOM, $val['start_time'].'+00:00', $timezone))) continue;
		
		$events[] = $start_time->format('D j M H:i');
	}
	if ( empty($events) ) return;

	$event_str = implode(', ', $events);
	;*/
	return sprintf('<p %1$s>%2$s</p>', 'class="exhibit-header__event-time"', $event_str);
}
