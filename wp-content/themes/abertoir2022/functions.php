<?php
// enqueue child theme and parent styles
add_action( 'wp_enqueue_scripts', 'child_theme_styles');
function child_theme_styles() {
    wp_enqueue_style( 'abertoir-style', get_stylesheet_directory_uri().'/css/styles.min.css',
        array( 'twentytwentytwo-style' ),
        wp_get_theme()->get('Version') 
    );

    wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/scripts.min.js', array(), wp_get_theme()->get('Version'), true);

    wp_enqueue_style( 'flickity-style', get_stylesheet_directory_uri().'/css/flickity.css',
        array( 'abertoir-style' ),
        wp_get_theme()->get('Version') 
    );
    wp_enqueue_script( 'flickity', get_stylesheet_directory_uri() . '/js/flickity.pkgd.min.js', array(), wp_get_theme()->get('Version'), true);
}

// support editor styles
add_theme_support('editor-styles');
add_editor_style('css/editor-styles.css');

// Adding a new (custom) block category and show that category at the top
function abertoir_block_category( $categories, $post ) {
	
	array_unshift( $categories, [
		'slug'	=> 'abertoir',
		'title' => 'Abertoir',
	] );

	return $categories;
}
add_filter( 'block_categories_all', 'abertoir_block_category', 10, 2);

// include custom blocks
include(__DIR__.'/blocks/lang-switcher.php');
include(__DIR__.'/blocks/festival-dates.php');
include(__DIR__.'/blocks/exhibit-tag.php');
include(__DIR__.'/blocks/event-time.php');
include(__DIR__.'/blocks/exhibit-meta.php');
include(__DIR__.'/blocks/schedule.php');
include(__DIR__.'/blocks/lineup.php');
include(__DIR__.'/blocks/lineup-gallery.php');
include(__DIR__.'/blocks/aber-featured-image.php');
include(__DIR__.'/blocks/testimonials.php');

if ( function_exists( 'register_block_pattern_category' ) ) {
	register_block_pattern_category(
		'abertoir',
		array( 'label' => __( 'Abertoir', 'abertoir2022' ) )
	);
}

// remove core patterns for clean slate
add_action( 'init', function() {
    remove_theme_support( 'core-block-patterns' );
    unregister_block_pattern_category( 'theme-slug-query' );
    add_post_type_support( 'page', 'excerpt' );
}, 0 );

// change query block loop results
add_filter( 'query_loop_block_query_vars', function ( $query ) {
    // ignore if the query block is not using this post type
    if ( 'exhibit' !== $query['post_type'] ) {
        return $query;
    }

    /**
     * @var WP_Post
     */
    global $post;

    if ($post) {
        // is the post a festival?
        $tpl_slug = get_page_template_slug($post);

        if ($tpl_slug === "wp-custom-template-festival") {
            // alter query so we only get direct children of the festival
            $query['post_parent__in'] = [$post->ID];
        }
    }

    return $query;
});

// hook into custom plugins
// yoast
add_filter( 'wpseo_breadcrumb_links', 'wpse_100012_override_yoast_breadcrumb_trail' );
function wpse_100012_override_yoast_breadcrumb_trail( $links ) {
    // remove current page
   // array_pop($links);
    // remove home page
    array_shift($links);

    return $links;
}

add_filter('wpseo_breadcrumb_separator', 'filter_wpseo_breadcrumb_separator', 10, 1);
function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
    $icon_uri = get_stylesheet_directory_uri() . '/assets/images/skull.svg';
    $svg = file_get_contents($icon_uri);
    return sprintf('<span class="breadcrumb__separator">%s</span>', $svg);
};

/*
add_filter( 'rest_prepare_taxonomy', function( $response, $taxonomy, $request ){
    $context = ! empty( $request['context'] ) ? $request['context'] : 'view';
    
            // Context is edit in the editor
            if( $context === 'edit' && $taxonomy->meta_box_cb === false ){
    
                $data_response = $response->get_data();
    
                $data_response['visibility']['show_ui'] = false;
    
                $response->set_data( $data_response );
            }
    
            return $response;
    }, 10, 3 );*/

// Add second featured image
add_action( 'add_meta_boxes', 'listing_image_add_metabox' );
function listing_image_add_metabox () {
    add_meta_box( 'listingimagediv', __( 'Onesheet Poster', 'abertoir2022' ), 'listing_image_metabox', 'exhibit', 'side', 'low');
}

function listing_image_metabox ( $post ) {
    global $content_width, $_wp_additional_image_sizes;

    $image_id = get_post_meta( $post->ID, '_listing_image_id', true );

    $old_content_width = $content_width;
    $content_width = 254;

    if ( $image_id && get_post( $image_id ) ) {

        if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
            $thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
        } else {
            $thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
        }

        if ( ! empty( $thumbnail_html ) ) {
            $content = $thumbnail_html;
            $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_listing_image_button" >' . esc_html__( 'Remove onesheet poster', 'text-domain' ) . '</a></p>';
            $content .= '<input type="hidden" id="upload_listing_image" name="_listing_cover_image" value="' . esc_attr( $image_id ) . '" />';
        }

        $content_width = $old_content_width;
    } else {

        $content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
        $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Add onesheet poster', 'abertoir2022' ) . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'text-domain' ) . '" data-uploader_button_text="' . esc_attr__( 'Set onesheet poster image', 'text-domain' ) . '">' . esc_html__( 'Set listing image', 'text-domain' ) . '</a></p>';
        $content .= '<input type="hidden" id="upload_listing_image" name="_listing_cover_image" value="" />';

    }

    echo $content;
}

add_action( 'save_post', 'listing_image_save', 10, 1 );
function listing_image_save ( $post_id ) {
    if( isset( $_POST['_listing_cover_image'] ) ) {
        $image_id = (int) $_POST['_listing_cover_image'];
        update_post_meta( $post_id, '_listing_image_id', $image_id );
    }
}


function wpdocs_selectively_enqueue_admin_script( $hook ) {
    if ( 'post.php' != $hook ) {
        return;
    }
    wp_enqueue_script( 'secondimage', get_stylesheet_directory_uri() . '/js/secondimage.js', array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );

/**
 * Add image sizes.
 */
add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
function wpdocs_theme_setup() {
    add_image_size( 'square_2x', 600, 600, true );// 1 / 1
    add_image_size( 'hd', 1920, 1080, true );// 16 / 9
	add_image_size( 'panoramic', 1600, 600, true );// 8 / 3
    add_image_size( 'onesheet', 240, 360, true );// 2 / 3
    add_image_size( 'onesheet_2x', 480, 720, true );// 2 / 3
    add_image_size( 'teaser', 340, 204, true );// 5 / 3
    add_image_size( 'teaser_2x', 680, 408, true );// 5 / 3
}

/**
 * Ensures that exhibits have the correct post template set based on the festival category.
 */
add_action('save_post','save_post_callback');
function save_post_callback($post_id){
    $post = get_post($post_id); 

    if (!$post || $post->post_type != 'exhibit'){
        return;
    }

    $slugs = [];
    $terms = get_the_terms($post, 'festival_category');
    $template = get_page_template_slug($post);

    if ($terms) {
        foreach($terms as $term) {
            $slugs[] = $term->slug;
        }

        if (in_array('festival', $slugs)) {
            if ($template !== 'wp-custom-template-festival') {
                update_metadata('post',  $post_id, '_wp_page_template', 'wp-custom-template-festival' );
            }
        }
        elseif (in_array('line-up', $slugs)) {
            if ($template !== 'wp-custom-template-line-up') {
                update_metadata('post',  $post_id, '_wp_page_template', 'wp-custom-template-line-up' );
            }
        }
        elseif (in_array('film', $slugs) || in_array('event', $slugs)) {
            if ($template !== 'wp-custom-template-film') {
                update_metadata('post',  $post_id, '_wp_page_template', 'wp-custom-template-film' );
            }
        }
        else {
            if ($template !== 'single-exhibit') {
                update_metadata('post',  $post_id, '_wp_page_template', 'single-exhibit' );
            }
        }
    }
    else {
        if ($template !== 'single-exhibit') {
            update_metadata('post',  $post_id, '_wp_page_template', 'single-exhibit' );
        }
    }
}

/**
 * Filter post featured image block and add the image caption.
 */
function include_feature_image_caption($block_content, $block){
    if ( isset($block['attrs']['className']) && $block['attrs']['className'] === 'aber-featured-image') {
        $caption = '<figcaption class="aber-featured-image__caption">' . get_the_post_thumbnail_caption() . '</figcaption>';
        $block_content = str_replace('</figure>', $caption . '</figure>', $block_content);
        return $block_content;
    }
    return $block_content;
}
add_filter( 'render_block_core/post-featured-image', 'include_feature_image_caption', 10, 2 );

/**
 * Allow unpublished items to appear in list of parents on exhibit edit screen.
 */
add_filter('rest_exhibit_query', 'aber_exhibit_dropdown_pages_args', 10, 12);
add_filter('quick_edit_dropdown_pages_args', 'aber_quick_edit_dropdown_pages_args', 1, 1);
function aber_exhibit_dropdown_pages_args( $args, $request) {
    $args['post_status'] = ['publish', 'draft'];
    return $args;
}
function aber_quick_edit_dropdown_pages_args($dropdown_args) {
    $dropdown_args['post_status'] = array('publish','draft');
    return $dropdown_args;
}
