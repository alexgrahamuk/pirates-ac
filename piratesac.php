<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://a-graham.com
 * @since             1.0.0
 * @package           Piratesac
 *
 * @wordpress-plugin
 * Plugin Name:       Piratesac
 * Plugin URI:        https://a-graham.com
 * Description:       Accessibility tools
 * Version:           1.0.0
 * Author:            A Graham
 * Author URI:        https://a-graham.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       piratesac
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
define( 'PIRATESAC_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-piratesac-activator.php
 */
function activate_piratesac() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-piratesac-activator.php';
    Piratesac_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-piratesac-deactivator.php
 */
function deactivate_piratesac() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-piratesac-deactivator.php';
    Piratesac_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_piratesac' );
register_deactivation_hook( __FILE__, 'deactivate_piratesac' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-piratesac.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_piratesac() {

	$plugin = new Piratesac();
	$plugin->run();

}
run_piratesac();
