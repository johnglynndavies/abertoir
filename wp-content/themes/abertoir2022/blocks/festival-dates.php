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

	$editor_css = 'festival-dates/css/editor.min.css';
	wp_register_style(
		'festival-dates-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'festival-dates/css/style.min.css';
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

	$option = get_option( 'film-festivals_name' );
	if (empty($option['dateson']) || (!empty($option['dateson']) && $option['dateson'] != 'on')) {
		return;
	}

	// get date range from latest festival exhibit
	if (($festival = $option['festivaldates'] ?? NULL)) {
		$timezone = new DateTimeZone('Europe/London');

		if ($date = get_post_meta($festival, '_event_date', true)) {
			sanitise_festival_date($date);
			$date = DateTime::createFromFormat(DATE_ATOM, $date.'+00:00', $timezone);
		}

		if ($enddate = get_post_meta($festival, '_event_enddate', true)) {
			sanitise_festival_date($enddate);
			$enddate = DateTime::createFromFormat(DATE_ATOM, $enddate.'+00:00', $timezone);
		}
		
		if ($date && $enddate) {
			$date_format = $date->format('m') != $enddate->format('m') ? 'j M' : 'j';
			$enddate_format = $date->format('m') != $enddate->format('m') ? 'j M Y' : 'j F Y';
			$festival_dates = sprintf("%s – %s", $date->format($date_format), $enddate->format($enddate_format));
		}
	}

	if ( empty($festival_dates) ) {
		return;
	}

	return sprintf('<p %1$s>%2$s</p>', 'class="aber-header__dates"', $festival_dates);
}

function sanitise_festival_date(string &$date) 
{
	if (substr_count($date, ':') === 1) {
    	$date .= ':00';
    }

	return $date;
}
