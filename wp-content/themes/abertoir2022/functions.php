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

// support editor styles
add_theme_support('editor-styles');
add_editor_style(get_stylesheet_directory_uri().'/css/editor-styles.css');

// include custom blocks
include(__DIR__.'/blocks/lang-switcher.php');
include(__DIR__.'/blocks/festival-dates.php');