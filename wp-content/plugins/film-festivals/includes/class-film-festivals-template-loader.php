<?php

if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  require FILM_FESTIVALS_PLUGIN_DIR . 'includes/class-gamajo-template-loader.php';
}

/**
 * Template Loader class.
 *
 * @package    Film_Festivals
 * @subpackage Film_Festivals/includes
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Template_Loader extends Gamajo_Template_Loader {

  /**
   * Prefix for filter names.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $filter_prefix = 'film_festivals';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * For example: 'your-plugin-templates'.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $theme_template_directory = 'film-festivals';

  /**
   * Reference to the root directory path of this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * e.g. YOUR_PLUGIN_TEMPLATE or plugin_dir_path( dirname( __FILE__ ) ); etc.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $plugin_directory = FILM_FESTIVALS_PLUGIN_DIR;

  /**
   * Directory name where templates are found in this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * e.g. 'templates' or 'includes/templates', etc.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $plugin_template_directory = 'public/templates';

}
