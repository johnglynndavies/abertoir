<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * This class defines 
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/admin
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Admin_Page {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Path to the admin page templates.
   *
   * @var string
   */
  private $template_path;

  /**
   * Holds the values to be used in the fields callbacks
   */
  public $options;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param    string    $plugin_name       The name of this plugin.
   */
  public function __construct( $plugin_name ) {
    $this->plugin_name = $plugin_name;
    $this->template_path = FILM_FESTIVALS_PLUGIN_DIR.'admin/templates';
  }

  public function get_page_arguments() {
    return [
      $this->get_title(),
      'Film Festivals',
      'manage_options',
      $this->plugin_name,
      [$this, 'render_page'],
      'dashicons-video-alt2',
      5,
    ];
  }

  public function get_title()
  {
    return __( 'Film Festivals', $this->plugin_name);
  }

  /**
   * Configure the admin page using the Settings API.
   */
  public function configure()
  {
      // Register settings
      register_setting($this->plugin_name . '_settings', $this->plugin_name . '_name', [$this, 'sanitise']);

      // Register section and field
      add_settings_section(
          $this->plugin_name . '_settings',
          __('Settings', $this->plugin_name),
          array($this, 'render_settings'),
          $this->plugin_name
      );
      add_settings_field(
          $this->plugin_name . '_dateson', 
          __('Festival Dates On/Off', $this->plugin_name),
          array($this, 'render_dateson'), 
          $this->plugin_name, 
          $this->plugin_name . '_settings'
      );
      
  }

  public function render_page() {
    $this->render_template($this->plugin_name . '-page');
  }

  public function render_settings()
  {
    $this->render_template('components/settings');
  }

  public function render_dateson()
  {
    $this->options = get_option( $this->plugin_name . '_name' );
    $this->render_template('components/dateson'); 
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function sanitise( $input )
  {
    settings_errors();
      /*if( !is_numeric( $input['id_number'] ) )
          $input['id_number'] = '';  

      if( !empty( $input['title'] ) )
          $input['title'] = sanitize_text_field( $input['title'] );*/

      return $input;
  }

  /**
   * Renders the given template if it's readable.
   *
   * @param string $template
   */
  private function render_template($template)
  {
      $template_path = $this->template_path . '/' . $template . '.php';

      if (!is_readable($template_path)) {

          return;
      }

      include $template_path;
  }

  public function categories()
  {
    $categories = [];
    $all_categories = get_categories(['taxonomy' => 'festival_category', 'orderby' => 'id', 'order' => 'DESC', "hide_empty" => false]);

    foreach($all_categories as $category) {
      // only display default language options. 
      $lang = pll_get_term_language($category->term_id, 'slug');
      if ($lang !== pll_default_language('slug')) continue;

      $categories[$category->term_id] = $category->name;
    }

    return $categories;
  }
  
}
