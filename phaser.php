<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              coolblueweb.com
 * @since             1.0.0
 * @package           Phaser
 *
 * @wordpress-plugin
 * Plugin Name:       Phaser
 * Plugin URI:        coolblueweb.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Daniel Jackson
 * Author URI:        coolblueweb.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       phaser
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-phaser-activator.php
 */
function activate_phaser() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-phaser-activator.php';
	Phaser_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-phaser-deactivator.php
 */
function deactivate_phaser() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-phaser-deactivator.php';
	Phaser_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_phaser' );
register_deactivation_hook( __FILE__, 'deactivate_phaser' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-phaser.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_phaser() {

	$plugin = new Phaser();
	$plugin->run();

}
run_phaser();
