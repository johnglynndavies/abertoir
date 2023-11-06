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
function lineup_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'lineup/index.js';
	wp_register_script(
		'lineup-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'lineup/css/editor.min.css';
	wp_register_style(
		'lineup-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'lineup/css/style.min.css';
	wp_register_style(
		'lineup-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/lineup', [
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
			'alignWide' => true,
		],
		'title' => 'Programme line-up',
		'category' => 'abertoir',
		'editor_script' => 'lineup-block-editor',
		'editor_style'  => 'lineup-block-editor',
		'style'         => 'lineup-block',
		'render_callback' => 'render_block_lineup',
	] );
}

add_action( 'init', 'lineup_block_init' );

function render_block_lineup( $attributes, $content ) {
	$lineup = '<div class="lineup alignwide">';
	//$wrapper_attributes = get_block_wrapper_attributes();

	/**
	 * @var WP_Post
	 */
	global $post;

	$filtered = false;
	$lineup_order = get_query_var('lineup_order') ?: null;
	$lineup_filter = get_query_var('lineup_filter') ?: null;
	$lineup_type = get_query_var('lineup_type') ?: null;

	$tax_query = [];
	$meta_compare = ($lineup_filter ? '>=' : NULL);
	$meta_value = ($lineup_filter ? (new DateTime())->format('Y-m-d H:i:s') : NULL);
	$orderby = ($lineup_order === 'schedule' ? 'meta_value' : 'title');

	if ($lineup_type && $lineup_type !== 'all') {
		$term = ($lineup_type === 'event' ? 'event' : ($lineup_type === 'film' ? 'film' : ''));
		$tax_query = [[
			'taxonomy' => 'festival_category',
			'field'    => 'slug',
			'terms'    => $term,
		]];
	}

	$args = [
		'post_type' => 'exhibit',
        'tax_query' => $tax_query,
        'meta_query' => NULL,
        'meta_key' => '_event_date',
        'meta_value' => $meta_value,
		'meta_compare' => $meta_compare,
        'post__in' => NULL,
        'post__not_in' => NULL,
		'post_parent__in' => [$post->ID],
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => $orderby, 
        'order' => 'ASC', 
	];

	$loop = new WP_Query( $args );

	$post_link = get_post_permalink($post);

	$q_params = [
		'title' => [
			'lineup_order' => 'title'
		],
		'schedule' => [
			'lineup_order' => 'schedule'
		],
		'upcoming' => [
			'lineup_filter' => 'upcoming',
		],
		'all' => [
			'lineup_type' => 'all',
		],
		'films' => [
			'lineup_type' => 'film',
		],
		'events' => [
			'lineup_type' => 'event',
		],
	];

	if ($lineup_filter) {
		$filtered = true;

		foreach($q_params as $key => $params) {
			if ($key === 'all') continue;
			if (!isset($q_params[$key]['lineup_filter'])) $q_params[$key]['lineup_filter'] = $lineup_filter;
		}
	}

	if ($lineup_order) {
		foreach($q_params as $key => $params) {
			if (!isset($q_params[$key]['lineup_order'])) $q_params[$key]['lineup_order'] = $lineup_order;
		}
	}

	if ($lineup_type) {
		$filtered = true;

		foreach($q_params as $key => $params) {
			if ($key === 'upcoming' && $lineup_type === 'all') continue;
			if (!isset($q_params[$key]['lineup_type'])) $q_params[$key]['lineup_type'] = $lineup_type;
		}
	}

	$lineup .= '
		<div class="lineup-exhibit__controls">
			<p>Order by:
				<a class="aber-tag'.(is_null($lineup_order) || $lineup_order === 'title' ? ' aber-tag--active' : '').'" href="'.$post_link.'?'.http_build_query($q_params['title']).'">title</a>
				<a class="aber-tag'.($lineup_order === 'schedule' ? ' aber-tag--active' : '').'" href="'.$post_link.'?'.http_build_query($q_params['schedule']).'">schedule</a>
			</p>
			<p>Show:
				<a class="aber-tag'.(is_null($lineup_filter) && (is_null($lineup_type) || $lineup_type === 'all') ? ' aber-tag--active' : '').'" href="'.$post_link.'?'.http_build_query($q_params['all']).'">full programme</a>
				<a class="aber-tag'.($lineup_type === 'film' ? ' aber-tag--active' : '').'" href="'.$post_link.'?'.http_build_query($q_params['films']).'">films</a>
				<a class="aber-tag'.($lineup_type === 'event' ? ' aber-tag--active' : '').'" href="'.$post_link.'?'.http_build_query($q_params['events']).'">events</a>
				<a class="aber-tag'.($lineup_filter === 'upcoming' ? ' aber-tag--active' : '').'" href="'.$post_link.'?'.http_build_query($q_params['upcoming']).'">upcoming</a>
			</p>
		</div>
		';

	if ($loop->have_posts()) {  
		$lineup .= '<div class="lineup-exhibit__container">';

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

			$tags_str = '';

			if ($tags = get_the_terms($id, 'exhibit_tags')) {
				foreach($tags as $tag) {
					$tag_name = _aber_lineup_truncate_tag($tag->name);
					$tags_str .= ' <span class="lineup-exhibit__tag">'.$tag_name.'</span>';
				}
			}

			$picture = get_the_post_thumbnail( $post, 'onesheet' );

			if ($image_id = get_post_meta( $post->ID, '_listing_image_id', true )) {
				if ( $image_id && get_post( $image_id ) ) {
					$picture = wp_get_attachment_image( $image_id, 'onesheet' );
				}
			}
		
		  	$lineup .= '<div class="lineup-exhibit">
							<div class="lineup-exhibit__picture">'.$picture.'</div>
							<div class="lineup-exhibit__content">
								<h3 class="lineup-exhibit__title"><a href="'.get_the_permalink().'">'.get_the_title().'</a>'.$tags_str.'</h3>
								<p class="lineup-exhibit__meta">'.$event_date?->format('D j M H:i').'</p>
								<p class="lineup-exhibit__excerpt">'.get_the_excerpt().'</p>
							</div>
						</div>';
		endwhile; 

		$lineup .= '</div>';
  	} 
	else {
		if ($filtered) {
			$type = $term ?? 'events';
			$lineup .= "<p>Sorry, there are no {$type} to display with the selected filters. <a href=\"{$post_link}\">View this page without any filters</a>.</p>";
		}
		else {
			$lineup .= "<p>There are no events to display as part of this line up yet.</p>";
		}
	}

	$lineup .= '</div>';
	  
	wp_reset_postdata();
	
	return $lineup;
}

add_filter( 'query_vars', 'abertoir2022_query_vars' );
function abertoir2022_query_vars( $qvars ) {
	$qvars[] = 'lineup_order';
    $qvars[] = 'lineup_filter';
    $qvars[] = 'lineup_type';
	return $qvars;
}

function _aber_lineup_truncate_tag($tag) {
	if (preg_match('/(\+?\s?Q&amp;A)/', $tag, $matches)) {
		return $matches[0];
	}
	elseif (preg_match('/(\+?\s?Intro)/', $tag, $matches)) {
		return $matches[0];
	}
	elseif (preg_match('/(\+?\s?Presentation)/', $tag, $matches)) {
		return $matches[0];
	}

	return $tag;
}
