<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://webstilo.com
 * @since             1.0.0
 * @package           remove-media-library
 *
 * @wordpress-plugin
 * Plugin Name:       Remove Media Library
 * Plugin URI:        http://webstilo.com/remove-media-library/
 * Description:       Remove all media from WordPress Media Library.
 * Version:           1.0.0
 * Author:            WebStilo.com
 * Author URI:        http://webstilo.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       remove-media-library
 * Domain Path:       /languages
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
define( 'REMOVE_MEDIA_LIBRARY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-remove-media-library-activator.php
 */
function activate_remove_media_library() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-remove-media-library-activator.php';
	Remove_Media_Library_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-remove-media-library-deactivator.php
 */
function deactivate_remove_media_library() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-remove-media-library-deactivator.php';
	Remove_Media_Library_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_remove_media_library' );
register_deactivation_hook( __FILE__, 'deactivate_remove_media_library' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-remove-media-library.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_remove_media_library() {

	$plugin = new Remove_Media_Library();
	$plugin->run();

}
run_remove_media_library();