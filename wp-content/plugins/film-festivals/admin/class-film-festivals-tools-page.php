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
class Film_Festivals_Tools_Page {

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
      'Tools',
      'manage_options',
      $this->plugin_name,
      [$this, 'render_page'],
      false,
      5,
    ];
  }

  public function get_title()
  {
    return __( 'Tools', $this->plugin_name);
  }

  /**
   * Configure the admin page using the Settings API.
   */
  public function configure()
  {
      register_setting($this->plugin_name . '_exhibit', $this->plugin_name . '_tools', [$this, "handle_file_upload"]);

      add_settings_section(
        $this->plugin_name . '_tools',
        __('Exhibit options', $this->plugin_name),
        array($this, 'render_tools'),
        $this->plugin_name
      );

      add_settings_field(
          $this->plugin_name . '_upload', 
          __('Upload exhibit list (.csv)', $this->plugin_name),
          array($this, 'render_upload'), 
          $this->plugin_name, 
          $this->plugin_name . '_tools'
      );
      
  }

  public function render_page() {
    $this->render_template($this->plugin_name . '-tools');
  }

  public function render_tools()
  {
    $this->render_template('components/tools');
  }

  public function render_upload()
  {
    $this->options = get_option( $this->plugin_name . '_tools' );
    $this->render_template('components/upload'); 
  }

  function handle_file_upload($option)
  {
    if(!empty($_FILES["demo-file"]["tmp_name"]))
    {
        $urls = wp_handle_upload($_FILES["demo-file"], array('test_form' => FALSE));
        $temp = $urls["url"];
        return $temp;   
    }

    return $option;
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function sanitise( $input )
  {
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
  
}
