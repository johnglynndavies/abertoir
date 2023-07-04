<?php
/**
 * Plugin Name:       Icon Separator
 * Description:       A simple horizontal separator with icon.
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           1.1.5
 * Author:            Phi Phan
 * Author URI:        https://boldblocks.net
 *
 * @package   IconSeparator
 * @copyright Copyright(c) 2022, Phi Phan
 */

namespace IconSeparator;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define constants.
define( 'ICON_SEPARATOR_ROOT', __FILE__ );
define( 'ICON_SEPARATOR_PATH', trailingslashit( plugin_dir_path( ICON_SEPARATOR_ROOT ) ) );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function icon_separator_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', __NAMESPACE__ . '\\icon_separator_block_init' );

// Load icons library.
require_once __DIR__ . '/includes/icon-library.php';
