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
function lineup_gallery_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_stylesheet_directory() . '/blocks';

	$index_js = 'lineup-gallery/index.js';
	wp_register_script(
		'lineup-gallery-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$index_js}",
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( "{$dir}/{$index_js}" )
	);

	$editor_css = 'lineup-gallery/css/editor.min.css';
	wp_register_style(
		'lineup-gallery-block-editor',
		get_stylesheet_directory_uri() . "/blocks/{$editor_css}",
		[],
		filemtime( "{$dir}/{$editor_css}" )
	);

	$style_css = 'lineup-gallery/css/style.min.css';
	wp_register_style(
		'lineup-gallery-block',
		get_stylesheet_directory_uri() . "/blocks/{$style_css}",
		[],
		filemtime( "{$dir}/{$style_css}" )
	);

	register_block_type( 'abertoir2022/lineup-gallery', [
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
		'title' => 'Line-up gallery',
		'category' => 'abertoir',
		'editor_script' => 'lineup-gallery-block-editor',
		'editor_style'  => 'lineup-gallery-block-editor',
		'style'         => 'lineup-gallery-block',
		'render_callback' => 'render_block_lineup_gallery',
	] );
}

add_action( 'init', 'lineup_gallery_block_init' );

/**
 * Render lineup gallery.
 */
function render_block_lineup_gallery( $attributes, $content ) {
	
	$wrapper_attributes = get_block_wrapper_attributes();

	// programme slider takes precedence
	$option = get_option( 'film-festivals_name' );

	if (!empty($option['sliderselect'])) {
		$children = get_children([
			'post_parent' => $option['sliderselect'],
			'post_type' => 'exhibit',
			'post_status' => 'publish',
		]);

		foreach($children as $id => $child) {
			if (get_page_template_slug($child) === "wp-custom-template-line-up") {
				$lineup = get_children([
					'post_parent' => $id,
					'post_type' => 'exhibit',
					'post_status' => 'publish',
				]);
			}
		}

		$pictures = [];

		foreach($lineup as $id => $exhibit) {
			if ($panoramic = lineup_gallery_image_from_post_id($id, 'panoramic')) {
				if ($square = lineup_gallery_image_from_post_id($id, 'square_2x')) {
					$pictures[] = lineup_gallery_block_figure($square[0], $panoramic[0], $panoramic['alt'], $panoramic['caption']);
				}
			}
		}
	}// otherwise check for custom slider images
	elseif (!empty(($images = get_option( 'film-festivals_image' )))) {
		$pictures = [];

		foreach($images as $k => $image) {
			if (empty($image['item'])) continue;
			
			if ($panoramic = lineup_gallery_image($image['item'], 'panoramic')) {
				if ($square = lineup_gallery_image($image['item'], 'square_2x')) {
					$order = (int) (!empty($image['order']) || $image['order'] === '0' ? $image['order'] : $k);
					$caption = $image['title'] ?: $panoramic['caption'];// allow caption override
					
					if (isset($pictures[$order])) {
						$pictures[] = lineup_gallery_block_figure($square[0], $panoramic[0], $panoramic['alt'], $caption);
					} else {
						$pictures[$order] = lineup_gallery_block_figure($square[0], $panoramic[0], $panoramic['alt'], $caption);
					}
				}
			}
		}
	}

	if (!empty($pictures)) {
		ksort($pictures);
		$gallery = "<div class=\"lineup-gallery alignfull\" data-flickity='{\"imagesLoaded\": true, \"autoPlay\": true}'>".implode('', $pictures).'</div>';

		return html_entity_decode($gallery);
	}
}

/**
 * Get lineup gallery image array from post id.
 */
function lineup_gallery_image_from_post_id(int $post_id, string $size) {
	$image = false;

	if (has_post_thumbnail($post_id)) {
		if ($post_thumbnail_id 	= get_post_thumbnail_id($post_id)) {
			return lineup_gallery_image($post_thumbnail_id, $size);
		}
	}

	return $image;
}

/**
 * Build lineup gallery image array.
 */
function lineup_gallery_image(int $post_thumbnail_id, string $size) {
	$image = false;
	
	if ($image = wp_get_attachment_image_src($post_thumbnail_id, $size)) {
		$image['alt'] = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', TRUE);
		$image['caption'] = wp_get_attachment_caption($post_thumbnail_id);
	}

	return $image;
}

/**
 * Return figure markup for gallery.
 */
function lineup_gallery_block_figure($sm, $lg, $alt, $caption)
{
	return '<figure>
		<picture class="carousel-cell">
			<source srcset="'.$sm.'" media="(max-width: 768px)">
			<img alt="'.$alt.'" src="'.$lg.'" />
		</picture>
		'.($caption ? '<figcaption>'.$caption.'</figcaption>' : '').'
	</figure>';
}
