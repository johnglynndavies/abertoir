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
function schedule_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'schedule/index.js';
	wp_register_script(
		'schedule-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'schedule/css/editor.min.css';
	wp_register_style(
		'schedule-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'schedule/css/style.min.css';
	wp_register_style(
		'schedule-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/schedule', [
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
		],
		'title' => 'Festival Schedule',
		'category' => 'aber-blocks',
		'editor_script' => 'schedule-block-editor',
		'editor_style'  => 'schedule-block-editor',
		'style'         => 'schedule-block',
		'render_callback' => 'render_block_schedule',
	] );
}

add_action( 'init', 'schedule_block_init' );

function render_block_schedule( $attributes, $content ) {
	$schedule = '';
	$wrapper_attributes = get_block_wrapper_attributes();

	/**
	 * @var WP_Post
	 */
	global $post;
	// get direct children of festival
	$lineups = [];
	$children = get_children( ['post_parent' => $post->ID] );

	foreach($children as $id => $child_post) {
		// is this a line up?
		$tpl_slug = get_page_template_slug($child_post);

		if ($tpl_slug === "wp-custom-template-line-up") {
			$lineups[] = $id;
		}
	}

	$args = [
		'post_type' => 'exhibit',
        'tax_query' => NULL,
        'meta_query' => NULL,
        'meta_key' => '_event_date',
        'meta_value' => NULL,
        'post__in' => NULL,
        'post__not_in' => NULL,
		'post_parent__in' => $lineups,
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => 'meta_value', 
        'order' => 'ASC', 
	];

	$loop = new WP_Query( $args );

	if ($loop->have_posts()) {  
		$schedule .= '<div class="alignwide festival-schedule">';
		$d = 0;
		
		while ( $loop->have_posts() ) : $loop->the_post();
			$id = get_the_ID();

			if ($meta_value = get_post_meta($id, '_event_date', true)) {
				$timezone = new DateTimeZone('Europe/London');
				// sometimes wp seems to omit the below necessary for the format we are using
				// so we add it when we need it...
				if (substr_count($meta_value, ':') === 1) {
					$meta_value .= ':00';
				}

				$event_date = DateTime::createFromFormat(DATE_ATOM, $meta_value.'+00:00', $timezone);
			}

			if (empty($event_date)) {
				$event_date = new DateTime();
			}

			$this_d = $event_date->format('d');

			if ($this_d !== $d) {
				$schedule .= '<h3>'.$event_date->format('D j F').'</h3>';
				$d = $this_d;
			}

			
			$tags_str = '';

			if ($tags = get_the_terms($id, 'exhibit_tags')) {
				foreach($tags as $tag) {
					$tag_name = preg_match('/(\+?\s?Q&amp;A)/', $tag->name, $matches) ? $matches[0] : $tag->name;
					$tags_str .= ' <span class="festival-schedule__tag">'.$tag_name.'</span>';
				}
			}
		
		  	$schedule .= '<div class="festival-schedule__row is-layout-flex has-medium-font-size">
		  				<p class="festival-schedule__time">'.$event_date?->format('H:i').'</p>
						<p class="festival-schedule__exhibit"><a href="'.get_the_permalink().'">'.get_the_title().'</a>'.$tags_str.'</p>
						</div>';
		endwhile; 
  
		$schedule .= '</div>';
	} 
	  
	wp_reset_postdata();

	return $schedule;
}
