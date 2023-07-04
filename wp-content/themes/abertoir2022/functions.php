<?php
// enqueue child theme and parent styles
add_action( 'wp_enqueue_scripts', 'child_theme_styles');
function child_theme_styles() {
    wp_enqueue_style( 'abertoir-style', get_stylesheet_directory_uri().'/css/styles.min.css',
        array( 'twentytwentytwo-style' ),
        wp_get_theme()->get('Version') 
);

    wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/scripts.min.js', array(), wp_get_theme()->get('Version'), true);
}

// Add block patterns
require get_stylesheet_directory() . '/inc/block-patterns.php';

// support editor styles
add_theme_support('editor-styles');
add_editor_style(get_stylesheet_directory_uri().'/css/editor-styles.css');

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

function gutenberg_examples_01_register_block() {
    register_block_type( __DIR__ .'/blocks/meta-block/');
}
add_action( 'init', 'gutenberg_examples_01_register_block' );

// hook into custom plugins

// change breadcrumb markup
add_filter( 'breadcrumb_block_get_breadcrumb_trail', function ( $markup, $args, $breadcrumbs_instance ) {
	return $markup;
}, 10, 3 );

// add/remove breadcrumb items
add_filter( 'breadcrumb_block_get_items', function ( $items, $breadcrumbs_instance ) {
    if (!$categories = get_terms('festival_category')) {
        return $items;
    }

    $pos = 1;
    $base_url = $items[0][1];
    $category_items = [];
    foreach($categories as $category) {
        $category_items[] = [$category->name, $base_url.$category->slug.'/'];
    }

    $new_items = array_merge(array_slice($items, 0, $pos), $category_items, array_slice($items, $pos));
	return $new_items;
}, 10, 2 );