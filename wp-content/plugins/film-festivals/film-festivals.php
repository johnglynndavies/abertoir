<?php
/**
 * Plugin Name: Film Festivals
 * Author: John Davies
 * Author URI: https://www.johnglynndavies.co.uk
 * Description: Generates Custom Post Types and Categories.
 * Version: 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FILM_FESTIVALS_VERSION', '1.0.0' );

/**
 * Define plugin directory
 */
define( 'FILM_FESTIVALS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_film_festivals() {
  require_once FILM_FESTIVALS_PLUGIN_DIR . 'includes/class-film-festivals-activator.php';
  $plugin_activator = new Film_Festivals_Activator();
  $plugin_activator->activate();
}

register_activation_hook( __FILE__, 'activate_film_festivals' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require FILM_FESTIVALS_PLUGIN_DIR . 'includes/class-film-festivals.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_film_festivals() {
  $plugin = new Film_Festivals();
  $plugin->run();
}

run_film_festivals();

if ( ! function_exists( 'watershed_blocks_post_grid' ) ) {
  /**
   * Prints post grid HTML.
   *
   * @since 1.0.0
   */
  function film_festivals_post_grid($args, $type) {
    require_once FILM_FESTIVALS_PLUGIN_DIR . 'public/class-film-festivals-post-grid.php';
    $query = new Film_Festivals_Post_Grid($args, $type);
    return $query->render();
  }
}

