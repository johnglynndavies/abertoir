<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Film_Festivals
 * @subpackage Film_Festivals/public
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Public {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/film-festivals-public.css', array(), $this->version, 'all' );

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

   // wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/film-festivals-public.js', array( 'jquery' ), $this->version, false );

  }

  /**
   * Get archive templates for custom post types.
   */
  function set_custom_post_type_template($template, $type, $templates) {
    global $post;
    $template_types = ['single'];    
    $post_types = ['exhibit'];

    foreach ($template_types as $template_type) {
      if ($template_type == $type) {
        foreach ($post_types as $post_type) {
          if ( ($post->post_type == $post_type) && !$this->is_template($template, $template_type, $post_type) ) {
            $template = plugin_dir_path( dirname( __FILE__ ) )."public/templates/{$template_type}-{$post_type}.php";
          }
        }
      }
    }

    return $template;

  }

  /**
   * Set theme overrideable template for custom taxonomy pages.
   */
  public function set_template($template) {
    $taxonomies = ['festival_category'];

    foreach ($taxonomies as $taxonomy) {
      //Check if the taxonomy is being viewed 
      if ( is_tax($taxonomy) && !$this->is_template($template, 'taxonomy', $taxonomy) ) {
        $template = plugin_dir_path( dirname( __FILE__ ) ) ."public/templates/taxonomy-{$taxonomy}.php";
      }
    }

    return $template;

  }

  /**
   * Check loaded template matches plugin template.
   */
  private function is_template($template_path, $template_type, $slug = NULL) {

    $template = basename($template_path);

    //single-exhibit.php || taxonomy-festival_category.php || taxonomy-festival_category-{term-slug}.php
    if ( 1 == preg_match("/^".$template_type.($slug ? "-".$slug : "")."((-(\S*))?).php/", $template) ) {
      return true;
    }

    return false;

  }

}
