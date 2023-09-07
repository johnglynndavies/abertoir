<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/includes
 * @author     John Davies <johnglynndavies.co.uk>
 */
class Film_Festivals {

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * The plugin directory.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_dir    The directory of the plugin.
   */
  protected $plugin_dir;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Film_Festivals_Loader    $loader   Registers all actions and filters for the plugin.
   */
  protected $loader;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {
    if ( defined( 'FILM_FESTIVALS_VERSION' ) ) {
      $this->version = FILM_FESTIVALS_VERSION;
    }
    else {
      $this->version = '1.0.0';
    }
    $this->plugin_name = 'film-festivals';

    if ( defined( 'FILM_FESTIVALS_PLUGIN_DIR' ) ) {
      $this->plugin_dir = FILM_FESTIVALS_PLUGIN_DIR;
    }
    else {
      $this->plugin_dir = plugin_dir_path( dirname( __FILE__ ) );
    }

    $this->load_dependencies();
    $this->define_admin_hooks();
    $this->define_public_hooks();

  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Filmacademy_Categories_Loader. Orchestrates the hooks of the plugin.
   * - Filmacademy_Categories_Admin. Defines all hooks for the admin area.
   * - Filmacademy_Categories_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once $this->plugin_dir . 'includes/class-film-festivals-loader.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once $this->plugin_dir . 'admin/class-film-festivals-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once $this->plugin_dir . 'public/class-film-festivals-public.php';

    $this->loader = new Film_Festivals_Loader();

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {

    $plugin_admin = new Film_Festivals_Admin( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'init', $plugin_admin, 'create_post_types', 0 );
    $this->loader->add_action( 'init', $plugin_admin, 'create_meta_fields', 0 );
    $this->loader->add_action( 'init', $plugin_admin, 'add_terms', 0 );
    $this->loader->add_action( 'init', $plugin_admin, 'film_festivals_upload', 0 );
    //$this->loader->add_filter( 'post_type_link', $plugin_admin, 'rewrite_permalinks', 10, 2 );
    //$this->loader->add_action( 'init', $plugin_admin, 'add_rewrite_rules', 500, 0 );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'create_film_festivals_admin', 0);
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'setup_post_type_submenu');
    $this->loader->add_action( 'parent_file', $plugin_admin, 'tax_menu_correction');
    $this->loader->add_action( 'admin_notices', $plugin_admin, 'upload_completion_notice', 0, 1 );

    // meta boxes
    $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_film_festival_metaboxes');
    $this->loader->add_action( 'save_post', $plugin_admin, 'save_film_festival_metaboxes', 1, 2);

    if ( ! class_exists( 'Film_Festivals_Admin_Page' ) ) {
      require_once $this->plugin_dir . 'admin/class-film-festivals-admin-page.php';
    }
    
    $admin_page = new Film_Festivals_Admin_Page( $this->get_plugin_name() );
    $this->loader->add_action( 'admin_enqueue_scripts', $admin_page, 'load_scripts', 1, 1 );
    $this->loader->add_action( 'admin_init', $admin_page, 'configure');

  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {

    $plugin_public = new Film_Festivals_Public( $this->get_plugin_name(), $this->get_version() );

    //$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    //$this->loader->add_action( 'template_include', $plugin_public, 'set_template' );
    //$this->loader->add_filter( 'single_template', $plugin_public, 'set_custom_post_type_template', 1, 3 );
    //$this->loader->add_filter( 'archive_template', $plugin_public, 'set_custom_post_type_template', 1, 3 );

    //$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

}
